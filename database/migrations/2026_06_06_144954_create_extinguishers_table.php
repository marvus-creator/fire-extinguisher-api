<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('extinguishers', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('location');
            $table->enum('type', ['Water', 'CO2', 'Foam', 'Dry Chemical']);
            $table->enum('size', ['2.5 lbs', '5 lbs', '9 lbs', '12 lbs']);
            $table->date('installation_date');
            $table->date('expiry_date');
            $table->enum('status', ['active', 'expired', 'maintenance'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extinguishers');
    }
};