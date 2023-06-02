<?php

namespace App\Http\Controllers\Supplier;

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
        return view("pages.supplier.cooperate.index")->with([
            "owners" => User::with(["roles", "media", "Owners"])->whereHas("roles", function ($query) {
                $query->whereName("Owner");
            })->whereHas("Owners", function ($query) {
                $query->whereSupplierId(Auth::user()->id);
            })->get()
        ]);
    }

    public function showOwner(User $user)
    {
        $cooperate = Cooperate::with("media")->whereOwnerId($user->id)->whereSupplierId(Auth::user()->id)->first();
        if ($cooperate) {
            return response()->json($cooperate);
        }
        return response()->json([
            "owner_id" => $user->id
        ]);
    }

    public function updateCooperateSchedule(Request $request, User $user)
    {
        $request->validate([
            "schedule_accepted" => "required|in:Disetujui,Ditolak",
        ]);

        DB::beginTransaction();
        try {
            $cooperate = Cooperate::whereOwnerId($user->id)->whereSupplierId(Auth::user()->id)->first();
            if (!$cooperate) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data tidak valid");
            }
            $cooperate->update([
                "schedule_accepted" => $request->schedule_accepted
            ]);

            DB::commit();
            return redirect()->back()->with("success", "Data berhasil disimpan");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function updateCooperate(Request $request, User $user)
    {
        $request->validate([
            "cooperate_letter" => "file|mimes:pdf",
            "cooperate_accepted" => "in:Disetujui,Ditolak",
        ]);

        DB::beginTransaction();
        try {
            $cooperate = Cooperate::whereOwnerId($user->id)->whereSupplierId(Auth::user()->id)->first();
            if (!$cooperate) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data tidak valid");
            }
            if ($request->cooperate_accepted == "Disetujui" && $request->cooperate_letter == null) {
                DB::rollBack();
                return redirect()->back()->with("error", "Jika anda menyetujui kerja sama, harap lampirkan suratnya");
            }

            $cooperate->update([
                "cooperate_accepted" => $request->cooperate_accepted
            ]);

            if ($request->cooperate_letter) {
                $cooperate->addMediaFromRequest("cooperate_letter")->toMediaCollection("cooperate-letter");
            }

            DB::commit();
            return redirect()->back()->with("success", "Data berhasil disimpan");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
}
