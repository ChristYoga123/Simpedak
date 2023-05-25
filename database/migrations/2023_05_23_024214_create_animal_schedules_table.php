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
        Schema::create('animal_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId("animal_owner_id")->constrained();
            $table->string("schedule_name");
            $table->time("schedule_time");
            $table->enum("schedule_type", ["Daily", "Yearly", "Monthly", "Weekly"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_schedules');
    }
};
