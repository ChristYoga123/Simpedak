<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ProductOwner;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        return view("pages.owner.transaction.index")->with([
            "transactions" => Transaction::with(["TransactionDetails", "TransactionDetails.ProductOwner.Product", "User"])->whereUserId(Auth::user()->id)->get(),
            "serve_products" => ProductOwner::with("Product")->whereUserId(Auth::user()->id)->whereHas("ProductType", function ($query) {
                $query->whereType("Bahan Jadi");
            })->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "contact" => "required|numeric",
            "address" => "required",
            "product" => "required|array",
            "product.*" => "integer|exists:product_owners,id",
            "quantity" => "required|array",
            "quantity.*" => "integer"
        ]);
        DB::beginTransaction();
        try {
            $total_price = 0;
            foreach ($request->product as $key => $product) {
                $product_owner_price = ProductOwner::findOrFail($product);
                $product_owner_price = $product_owner_price->ProductPrice->price * $request->quantity[$key];
                $total_price += $product_owner_price;
            }
            $transaction = Transaction::create([
                "user_id" => Auth::user()->id,
                "name" => $request->name,
                "contact" => $request->contact,
                "address" => $request->address,
                "total_price" => $total_price,

            ]);

            foreach ($request->product as $key => $product) {
                TransactionDetail::create([
                    "transaction_id" => $transaction->id,
                    "product_owner_id" => $product,
                    "quantity" => $request->quantity[$key]
                ]);
                $product_owner_quantity = ProductOwner::findOrFail($product);
                if ($product_owner_quantity->quantity - $request->quantity[$key] < 0) {
                    DB::rollBack();
                    return redirect()->back()->with("error", "Maaf produk yang dibeli melebihi stok yang dimiliki");
                }
                $product_owner_quantity->decrement("quantity", $request->quantity[$key]);
            }
            DB::commit();
            return redirect()->back()->with("success", "Data berhasil ditambahkan");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load("TransactionDetails.ProductOwner.Product");
        return response()->json([
            "name" => $transaction->name,
            "contact" => $transaction->contact,
            "address" => $transaction->address,
            "products" => $transaction->TransactionDetails,
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            "name" => "required",
            "contact" => "required|numeric",
            "address" => "required",
            "product" => "required|array",
            "product.*" => "integer|exists:product_owners,id",
            "quantity" => "required|array",
            "quantity.*" => "integer"
        ]);

        DB::beginTransaction();
        try {
            $total_price = 0;
            foreach ($request->product as $key => $product) {
                $product_owner_price = ProductOwner::findOrFail($product);
                $product_owner_price = $product_owner_price->ProductPrice->price * $request->quantity[$key];
                $total_price += $product_owner_price;
            }
            // delete relation first
            TransactionDetail::whereTransactionId($transaction->id)->delete();
            // update transaction data
            $transaction->update([
                "name" => $request->name,
                "contact" => $request->contact,
                "address" => $request->address,
                "total_price" => $total_price
            ]);
            // create new transaction detail
            foreach ($request->product as $key => $product) {
                TransactionDetail::create([
                    "transaction_id" => $transaction->id,
                    "product_owner_id" => $product,
                    "quantity" => $request->quantity[$key]
                ]);
                $product_owner_quantity = ProductOwner::findOrFail($product);
                if ($product_owner_quantity->quantity - $request->quantity[$key] < 0) {
                    DB::rollBack();
                    return redirect()->back()->with("error", "Maaf produk yang dibeli melebihi stok yang dimiliki");
                }
                $product_owner_quantity->decrement("quantity", $request->quantity[$key]);
            }
            DB::commit();
            return redirect()->back()->with("success", "Data berhasil ditambahkan");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
}
