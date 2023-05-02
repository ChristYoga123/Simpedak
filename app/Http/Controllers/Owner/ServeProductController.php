<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOwner;
use App\Models\ProductPrice;
use App\Models\ProductType;
use App\Models\ProductHistory;
use App\Models\Recipe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ServeProductController extends Controller
{
    public function index()
    {
        return view("pages.owner.serve-product.index")->with([
            "product_owners" => ProductOwner::with(["Product", "ProductType", "media"])->whereUserId(Auth::user()->id)->whereHas("ProductType", function ($query) {
                $query->whereType("Bahan Jadi");
            })->get(),
            "raw_product_owners" =>  ProductOwner::with(["Product"])->whereUserId(Auth::user()->id)->whereHas("ProductType", function ($query) {
                $query->whereType("Bahan Baku");
            })->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                "name" => "required",
                "unit" => "required|in:Piece,Package,Kilogram,Liter,Buah,Ton,Ekor",
                "price" => "required|integer",
                "image" => "required|image|mimes:png,jpg,jpeg|max:2048",
                "recipe" => "array",
                "recipe.*" => "nullable|required_with:recipe.*.1|exists:products,id",
                "quantity" => "array",
                "quantity.*" => "nullable|required_with:quantity.*.1|numeric|min:0.1"
            ],
            [
                "name.required" => "Kolom nama bahan harus diisi",
                "unit.required" => "Kolom satuan bahan harus diisi",
                "unit.in:Pieces,Packages,Kilogram,Liter,Buah,Ton" => "Pilihan satuan yang diisi harus sesuai dengan pilihan yang tersedia",
                "price.required" => "Kolom harga bahan harus diisi",
                "price.integer" => "Harga harus berupa angka",
                "image.required" => "Kolom gambar bahan harus diisi",
                "image.image" => "File yang dimasukkan harus file gambar",
                "image.mimes" => "Ekstensi file yang dapat dimasukkan harus png, jpg, jpeg",
                "image.max" => "Ukuran file yang dimasukkan minimal kurang atau sama dengan 2048 KB",
                "recipe.*.exists" => "Kolom resep harus diisikan oleh data produk dan bukan data asing",
                "quantity.*." => "Kolom kuantitas bahan baku harus diisi data angka",
                "quantity.*.min" => "Kuantitas bahan baku tidak boleh kurang dari 0"
            ]
        );
        DB::beginTransaction();
        try {
            $product = Product::firstOrCreate([
                "name" => Str::title($request->name),
                "slug" => Str::slug($request->slug)
            ]);

            if ($this->alreadyHasServeProduct($product->id)) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data nama produk jadi sudah teregistrasi sebelumnya. Pilih nama bahan baku lainnya!");
            }

            $product_owner = ProductOwner::create([
                "product_id" => $product->id,
                "user_id" => Auth::user()->id,
                "unit" => $request->unit
            ]);

            ProductType::create([
                "product_owner_id" => $product_owner->id,
                "type" => "Bahan Jadi"
            ]);

            ProductPrice::create([
                "product_owner_id" => $product_owner->id,
                "price" => $request->price
            ]);

            if (count($request->recipe) > 1 && count($request->quantity) > 1) {
                if (!$this->isNullRecipe($request->recipe, $request->quantity)) {
                    for ($i = 0; $i < count($request->recipe); $i++) {
                        Recipe::create([
                            "product_owner_id" => $product_owner->id,
                            "raw_product_id" => $request->recipe[$i],
                            "quantity" => $request->quantity[$i]
                        ]);
                    }
                } else {
                    DB::rollBack();
                    return redirect()->back()->with("error", "Kolom pertama bahan baku harap diisi");
                }
            } elseif (count($request->recipe) == 1 && count($request->quantity) == 1 && !$this->isNullRecipe($request->recipe, $request->quantity)) {
                Recipe::create([
                    "product_owner_id" => $product_owner->id,
                    "raw_product_id" => $request->recipe[0],
                    "quantity" => $request->quantity[0]
                ]);
            }

            $product_owner->addMediaFromRequest("image")->toMediaCollection("product-image");
            DB::commit();
            return redirect()->back()->with("success", "Data berhasil ditambahkan");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function show(ProductOwner $productOwner)
    {
        $productOwner->load('Product', 'ProductPrice', 'Recipes.RawProduct.Product');
        return response()->json([
            "name" => $productOwner->Product->name,
            "quantity" => $productOwner->quantity,
            "unit" => $productOwner->unit,
            "price" => $productOwner->ProductPrice->price,
            "image" => $productOwner->getFirstMediaUrl("product-image"),
            "recipes" => $productOwner->Recipes
        ]);
    }

    public function update(Request $request, ProductOwner $productOwner)
    {
        $request->validate(
            [
                "name" => "required",
                "unit" => "required|in:Piece,Package,Kilogram,Liter,Buah,Ton,Ekor",
                "price" => "required|integer",
                "image" => "image|mimes:png,jpg,jpeg|max:2048",
                "recipe" => "array",
                "recipe.*" => "nullable|required_with:recipe.*.1|exists:products,id",
                "quantity" => "array",
                "quantity.*" => "nullable|required_with:quantity.*.1|numeric|min:0.1"
            ],
            [
                "name.required" => "Kolom nama bahan harus diisi",
                "unit.required" => "Kolom satuan bahan harus diisi",
                "unit.in:Pieces,Packages,Kilogram,Liter,Buah,Ton" => "Pilihan satuan yang diisi harus sesuai dengan pilihan yang tersedia",
                "price.required" => "Kolom harga bahan harus diisi",
                "price.integer" => "Harga harus berupa angka",
                "image.image" => "File yang dimasukkan harus file gambar",
                "image.mimes" => "Ekstensi file yang dapat dimasukkan harus png, jpg, jpeg",
                "image.max" => "Ukuran file yang dimasukkan minimal kurang atau sama dengan 2048 KB",
                "recipe.*.exists" => "Kolom resep harus diisikan oleh data produk dan bukan data asing",
                "quantity.*." => "Kolom kuantitas bahan baku harus diisi data angka",
                "quantity.*.min" => "Kuantitas bahan baku tidak boleh kurang dari 0"
            ]
        );

        DB::beginTransaction();
        try {
            $product = Product::firstOrCreate([
                "name" => Str::title($request->name),
                "slug" => Str::slug($request->name)
            ]);

            if ($this->alreadyHasServeProduct($product->id) && $productOwner->Product->name != $product->name) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data nama produk jadi sudah teregistrasi sebelumnya. Pilih nama bahan baku lainnya!");
            }
            $productOwner->update([
                "product_id" => $product->id,
                "unit" => $request->unit
            ]);
            if (count($request->recipe) > 1 && count($request->quantity) > 1) {
                if (!$this->isNullRecipe($request->recipe, $request->quantity)) {
                    Recipe::whereProductOwnerId($productOwner->id)->delete();
                    for ($i = 0; $i < count($request->recipe); $i++) {
                        Recipe::create([
                            "product_owner_id" => $productOwner->id,
                            "raw_product_id" => $request->recipe[$i],
                            "quantity" => $request->quantity[$i]
                        ]);
                    }
                } else {
                    DB::rollBack();
                    return redirect()->back()->with("error", "Kolom pertama bahan baku harap diisi");
                }
            } elseif (count($request->recipe) == 1 && count($request->quantity) == 1 && $this->isNullRecipe($request->recipe, $request->quantity)) {
                Recipe::whereProductOwnerId($productOwner->id)->delete();
            } elseif (count($request->recipe) == 1 && count($request->quantity) == 1 && !$this->isNullRecipe($request->recipe, $request->quantity)) {
                Recipe::whereProductOwnerId($productOwner->id)->delete();
                Recipe::create([
                    "product_owner_id" => $productOwner->id,
                    "raw_product_id" => $request->recipe[0],
                    "quantity" => $request->quantity[0]
                ]);
            }
            ProductType::whereProductOwnerId($productOwner->id)->update([
                "product_owner_id" => $productOwner->id,
                "type" => "Bahan Jadi"
            ]);
            ProductPrice::whereProductOwnerId($productOwner->id)->update([
                "product_owner_id" => $productOwner->id,
                "price" => $request->price
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
            return redirect()->back()->with("success", $e->getMessage());
        }
    }

    public function updateQuantity(Request $request, ProductOwner $productOwner)
    {
        $request->validate([
            "quantity" => "required|numeric",
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
            foreach ($productOwner->Recipes as $recipe) {
                $quantity_result = $recipe->quantity * $request->quantity;
                $raw_product = ProductOwner::findOrFail($recipe->raw_product_id);
                if ($raw_product->quantity - $quantity_result < 0) {
                    DB::rollBack();
                    return redirect()->back()->with("error", "Maaf, bahan baku anda tidak mencukupi");
                }
                $raw_product->decrement("quantity", $quantity_result);
                ProductHistory::create([
                    "product_owner_id" => $recipe->raw_product_id,
                    "type" => "Pengurangan",
                    "quantity" => -$request->quantity,
                    "history" => "Digunakan untuk pembuatan " . $recipe->ProductOwner->Product->name . " sebesar " . $quantity_result
                ]);
            }
            DB::commit();
            return redirect()->back()->with("success", "Data berhasil diubah");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("success", $e->getMessage());
        }
    }
    private function alreadyHasServeProduct($product_id)
    {
        return ProductOwner::whereProductId($product_id)->whereUserId(Auth::user()->id)->exists();
    }

    private function isNullRecipe($recipe, $quantity)
    {
        return $recipe[0] === null && $quantity[0] === null;
    }
}
