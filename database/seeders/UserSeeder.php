<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            "name" => "Admin Simpedak",
            "email" => "adminsimpedak@gmail.com",
            "password" => bcrypt("password")
        ]);
        $admin->assignRole("Admin");

        $owner = User::create([
            "name" => "Burhan",
            "email" => "cvberkahetawa@gmail.com",
            "password" => bcrypt("password")
        ]);
        Business::create([
            "user_id" => $owner->id,
            "name" => "Berkah Etawa",
            "slug" => "berkah-etawa",
            "contact" => "082230555413",
            "address" => "Jalan Ikan Paus IV/D-12, Jember",
            "description" => "Pabrik pengolahan susu kambing etawa Jember"
        ]);

        $owner->assignRole("Owner");

        $supplier = User::create([
            "name" => "Pak Edi",
            "email" => "ediii23@gmail.com",
            "password" => bcrypt("password")
        ]);
        Business::create([
            "user_id" => $supplier->id,
            "name" => "Peternakan Pak Edi",
            "slug" => "peternakan-pak-edi",
            "contact" => "082337598195",
            "address" => "Jalan Ikan Paus IV/D-12, Jember",
            "description" => "Peternakan sapi"
        ]);
        $supplier->assignRole("Supplier");
    }
}
