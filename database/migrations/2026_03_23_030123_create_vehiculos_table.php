<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->string('plate')->unique();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('type')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('image')->nullable();

            $table->enum('status', ['available', 'unavailable', 'under maintenance'])->default('available');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};