<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        return view("pages.supplier.profile.index");
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            "name" => "required|unique:users,name," . $user->id,
            "email" => "required|email|unique:users,email, " . $user->id,
            "business_name" => "required|unique:businesses,name," . $user->Business->id,
            "contact" => "required|numeric|unique:businesses,contact," . $user->Business->id,
            "address" => "required",
            "avatar" => "image|mimes:jpg,png,jpeg",
            "description" => "required",
        ]);

        DB::beginTransaction();
        try {
            if ($request->password) {
                $user->update([
                    "name" => $request->name,
                    "email" => $request->email,
                    "password" => bcrypt($request->password)
                ]);
            } else {
                $user->update([
                    "name" => $request->name,
                    "email" => $request->email
                ]);
            }
            if ($request->avatar) {
                $user->clearMediaCollection("avatar");
                $user->addMediaFromRequest("avatar")->toMediaCollection("avatar");
            }
            Business::whereUserId($user->id)->update([
                "name" => $request->business_name,
                "slug" => Str::slug($request->business_name),
                "contact" => $request->contact,
                "address" => $request->address,
                "description" => $request->description
            ]);

            DB::commit();
            return redirect()->back()->with("success", "Data berhasil diubah");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
}
