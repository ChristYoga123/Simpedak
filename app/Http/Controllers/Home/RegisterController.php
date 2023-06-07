<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\ClientTransaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans;

class RegisterController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = env("MIDTRANS_SERVERKEY");
        \Midtrans\Config::$clientKey = env("MIDTRANS_CLIENTKEY");
        \Midtrans\Config::$isProduction = env("MIDTRANS_IS_PRODUCTION");
        \Midtrans\Config::$isSanitized = env("MIDTRANS_IS_SANITIZED");
        \Midtrans\Config::$is3ds = env("MIDTRANS_IS_3DS");
    }

    public function register_index()
    {
        return view("pages.home.register");
    }

    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|unique:users,name",
            "email" => "required|email|unique:users,email",
            "password" => "required",
            "role" => "required|in:Owner,Supplier",
            "contact" => "required|numeric|unique:businesses,contact",
            "business_name" => "required|unique:businesses,name",
            "avatar" => "required|image|mimes:jpg,png,jpeg",
            "business_galleries" => "required|array",
            "business_galleries.*" => "image|mimes:jpg,png,jpeg",
            "description" => "required",
            "business_permission_letter" => "required|file|mimes:pdf"
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password)
            ]);

            $user->addMediaFromRequest("avatar")->toMediaCollection("avatar");

            // assign Role
            $user->assignRole($request->role);

            // create user business
            $business = Business::create([
                "user_id" => $user->id,
                "name" => $request->business_name,
                "slug" => Str::slug($request->business_name),
                "contact" => $request->contact,
                "address" => $request->address,
                "description" => $request->description,
            ]);

            // insert business gallery
            foreach ($request->business_galleries as $business_gallery) {
                $business->addMedia($business_gallery)->toMediaCollection("business-galleries");
            }

            // insert business letter
            $business->addMediaFromRequest("business_permission_letter")->toMediaCollection();

            // insert clientTransaction
            $clientTransaction = ClientTransaction::create([
                "user_id" => $user->id
            ]);
            $this->getSnapRedirect($clientTransaction, $request->role);
            DB::commit();
            return redirect("{$clientTransaction->midtrans_url}");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function getSnapRedirect(ClientTransaction $clientTransaction, $role)
    {
        $transaction_details = [
            "order_id" => $clientTransaction->id . "-" . Str::random(5),
            "gross_ammount" => 200000
        ];

        $item_details[] = [
            "id" => $clientTransaction->id,
            "price" => 200000,
            "quantity" => 1,
            "name" => "Pembelian Dashboard Simpedak {$role}",
        ];

        $user_data = [
            "first_name" => $clientTransaction->User->name,
            "last_name" => "",
            "email" => $clientTransaction->User->email,
            "phone" => $clientTransaction->User->Business->contact,
            "address" => $clientTransaction->User->Business_address,
            "city" => "",
            "postal_code" => "",
            "country_code" => "IDN"
        ];

        $customer_details = [
            "first_name" => $clientTransaction->User->name,
            "last_name" => "",
            "email" => $clientTransaction->User->Business->email,
            "phone" => $clientTransaction->User->Business->contact,
            "billing_address" => $user_data,
            "shipping_address" => $user_data,
        ];

        $midtrans_parameter = [
            "transaction_details" => $transaction_details,
            "item_details" => $item_details,
            "customer_details" => $customer_details
        ];

        try {
            $payment_url = \Midtrans\Snap::createTransaction($midtrans_parameter)->redirect_url;
            $clientTransaction->midtrans_url = $payment_url;
            $clientTransaction->midtrans_booking_code = $transaction_details["order_id"];
            $clientTransaction->save();
        } catch (Exception $e) {
            return;
        }
    }

    public function midtransCallback(Request $request)
    {
        $notif = $request->method() == "POST" ? new Midtrans\Notification() : Midtrans\Transaction::status($request->order_id);

        $transaction_status = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $clientTransactionId = explode("-", $notif->order_id)[0];

        $clientTransaction = ClientTransaction::findOrFail($clientTransactionId);

        if ($transaction_status == 'capture') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'challenge'
                $clientTransaction->payment_status = "pending";
            } else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'success'
                $clientTransaction->payment_status = "paid";
            }
        } else if ($transaction_status == 'cancel') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'failure'
                $clientTransaction->payment_status = "failed";
            } else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'failure'
                $clientTransaction->payment_status = "failed";
            }
        } else if ($transaction_status == 'deny') {
            // TODO Set payment status in merchant's database to 'failure'
            $clientTransaction->payment_status = "failed";
        } else if ($transaction_status == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            $clientTransaction->payment_status = "paid";
        } else if ($transaction_status == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            $clientTransaction->payment_status = "pending";
        } else if ($transaction_status == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $clientTransaction->payment_status = "failed";
        }

        $clientTransaction->save();

        return redirect()->route("home.index");
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                "email.required" => "Kolom email harap diisi",
                "email.email" => "Kolom email harap diisi dengan format email yang benar",
                "password" => "Kolom password harap diisi"
            ]
        );

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route("home.index");
        }

        return redirect()->back()->with("error", "Maaf, akun atau password belum terdaftar. Harap registrasi terlebih dahulu");

        $credential = [$request->email, $request->password];

        // 
    }
}
