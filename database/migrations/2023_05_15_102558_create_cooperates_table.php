<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cooperates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("owner_id");
            $table->unsignedBigInteger("supplier_id");
            $table->foreign("owner_id")->references("id")->on("users");
            $table->foreign("supplier_id")->references("id")->on("users");
            $table->dateTime("meet_schedule");
            $table->enum("schedule_accepted", ["Menunggu", "Disetujui", "Ditolak"])->default("Menunggu");
            $table->enum("cooperate_accepted", ["Menunggu", "Disetujui", "Ditolak"])->default("Menunggu");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperates');
    }
};
