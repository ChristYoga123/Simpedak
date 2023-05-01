<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\ProductOwner;
use App\Models\ProductType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RawProductController extends Controller
{
    public function index()
    {
        return view("pages.owner.raw-products.index")->with([
            "product_owners" => ProductOwner::with(["ProductType", "Product", "media"])->whereUserId(Auth::user()->id)->whereHas("ProductType", function ($query) {
                $query->whereType("Bahan Baku");
            })->get()
        ]);
    }

    public function index_history($slug)
    {
        $productOwner = ProductOwner::whereHas("Product", function ($query) use ($slug) {
            $query->whereSlug($slug);
        })->whereUserId(Auth::user()->id)->first();
        return view("pages.owner.raw-products.history")->with([
            "histories" => ProductHistory::with("ProductOwner")->whereProductOwnerId($productOwner->id)->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                "name" => "required",
                "unit" => "required|in:Package,Piece,Kilogram,Ton,Liter,Buah,Ekor",
                "image" => "required|image|mimes:png,jpg,jpeg|max:2048"
            ],
            [
                "name.required" => "Kolom nama harus diisi",
                "unit.required" => "Kolom unit harus diisi",
                "unit.in" => "Nilai pada satuan harus Package,Piece,Kilogram,Ton,Liter,Buah,Ekor",
                "image.required" => "Kolom gambar harus diisi",
                "image.image" => "File yang dikirim harus berupa gambar",
                "image.mimes" => "Ekstensi file yang boleh masuk adalah png,jpg,jpeg",
                "image.max" => "Ukuran gambar maksimal 2048 KiloByte"
            ]
        );

        DB::beginTransaction();
        try {
            $product = Product::firstOrCreate([
                "name" => Str::title($request->name),
                "slug" => Str::slug($request->name),
            ]);

            // if user already has the product
            if ($this->alreadyHasRawProduct($product->id)) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data bahan baku sudah terdaftar sebelumnya. Anda tidak bisa memasukkan data yang sama");
            }
            $product_owner = ProductOwner::create([
                "user_id" => Auth::user()->id,
                "product_id" => $product->id,
                "unit" => $request->unit,
            ]);

            ProductType::create([
                "product_owner_id" => $product_owner->id,
                "type" => "Bahan Baku"
            ]);
            $product_owner->addMediaFromRequest("image")->toMediaCollection("product-image");
            DB::commit();
            return redirect()->back()->with("success", "Data berhasil disimpan");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function show(ProductOwner $productOwner)
    {
        $product_owner = ProductOwner::with("Product")->findOrFail($productOwner->id);
        return response()->json([
            "name" => $product_owner->Product->name,
            "quantity" => $product_owner->quantity,
            "unit" => $product_owner->unit,
            "image" => $product_owner->getFirstMediaUrl("product-image")
        ]);
    }

    public function update(Request $request, ProductOwner $productOwner)
    {
        $request->validate(
            [
                "name" => "required",
                "unit" => "required|in:Package,Piece,Kilogram,Ton,Liter,Buah,Ekor",
                "image" => "image|mimes:png,jpg,jpeg|max:2048"
            ],
            [
                "name.required" => "Kolom nama harus diisi",
                "unit.required" => "Kolom unit harus diisi",
                "unit.in" => "Nilai pada satuan harus Package,Piece,Kilogram,Ton,Liter,Buah,Ekor",
                "image.image" => "File yang dikirim harus berupa gambar",
                "image.mimes" => "Ekstensi file yang boleh masuk adalah png,jpg,jpeg",
                "image.max" => "Ukuran gambar maksimal 2048 KiloByte"
            ]
        );

        DB::beginTransaction();
        try {
            $product = Product::firstOrCreate([
                "name" => Str::title($request->name),
                "slug" => Str::slug($request->name),
            ]);

            // check if the request name is the registered name
            if ($this->alreadyHasRawProduct($product->id) && $productOwner->Product->name != $product->name) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data nama bahan baku sudah teregistrasi sebelumnya. Pilih nama bahan baku lainnya!");
            }
            // update
            $productOwner->update([
                "product_id" => $product->id,
                "unit" => $request->unit
            ]);

            if ($request->hasFile("image")) {
                $productOwner->clearMediaCollection("product-image");
                $productOwner->addMediaFromRequest("image")->toMediaCollection("product-image");
            } else {
                $media = Media::whereModelId($productOwner->id)->first();
                $media->model_id = $productOwner->id;
                $media->save();
            }

            DB::commit();
            return redirect()->back()->with("success", "Data berhasil diubah");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function updateQuantity(Request $request, ProductOwner $productOwner)
    {
        $request->validate([
            "quantity" => "required|numeric",
            "history" => "required"
        ]);
        DB::beginTransaction();
        try {
            if ($request->quantity == 0) {
                DB::rollBack();
                return redirect()->back()->with("error", "Tidak ada pengurangan atau penambahan kuantitas bahan baku yang terjadi. Data tidak akan tersimpan");
            } else if ($productOwner->quantity + $request->quantity < 0) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data kuantitas bahan baku tidak boleh kurang dari 0");
            }
            $productOwner->increment("quantity", $request->quantity);

            ProductHistory::create([
                "product_owner_id" => $productOwner->id,
                "type" => $request->quantity < 0 ? "Pengurangan" : "Penambahan",
                "quantity" => $request->quantity,
                "history" => $request->history,
            ]);
            DB::commit();
            return redirect()->back()->with("success", "Data berhasil diubah");
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    // decomposite if
    private function alreadyHasRawProduct($product_id)
    {
        return ProductOwner::whereProductId($product_id)->whereUserId(Auth::user()->id)->whereHas("ProductType", function ($query) {
            $query->whereType("Bahan Baku");
        })->exists();
    }
}
