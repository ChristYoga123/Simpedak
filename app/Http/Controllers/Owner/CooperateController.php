<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Cooperate;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CooperateController extends Controller
{
    public function index()
    {
        return view("pages.owner.cooperate.index")->with([
            "suppliers" => User::with(["roles", "media", "Suppliers"])->whereHas("roles", function ($query) {
                $query->whereName("Supplier");
            })->get()
        ]);
    }

    public function store(Request $request, $supplier_id)
    {
        $request->validate([
            "meet_schedule" => "required",
        ]);

        DB::beginTransaction();
        try {
            Cooperate::create([
                "owner_id" => Auth::user()->id,
                "supplier_id" => $supplier_id,
                "meet_schedule" => $request->meet_schedule
            ]);
            DB::commit();
            return redirect()->back()->with("success", "Data berhasil disimpan");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function showSupplier(User $user)
    {
        $cooperate = Cooperate::whereSupplierId($user->id)->whereOwnerId(Auth::user()->id)->first();
        if ($cooperate) {
            return response()->json($cooperate);
        }
        return response()->json([
            "supplier_id" => $user->id
        ]);
    }
}
