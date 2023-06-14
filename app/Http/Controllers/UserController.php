<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index')->with([
            "users" => User::with(["roles", "media", "Business"])->whereHas("roles", function ($query) {
                $query->where("name", "!=", "Admin");
            })->paginate()
        ]);
    }

    public function update(Request $request, User $user)
    {
        if (!$request->email_verified_at) {
            $user->email_verified_at = null;
            $user->save();
            return redirect()->back()->with("success", "Data berhasil diubah");
        }
        $currentTimestamp = time();
        $formattedTimestamp = date('Y-m-d H:i:s', $currentTimestamp);
        $user->email_verified_at = $formattedTimestamp;
        $user->save();
        return redirect()->back()->with("success", "Data berhasil diubah");
    }
}
