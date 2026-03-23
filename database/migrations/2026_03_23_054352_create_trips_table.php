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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('route_id')->nullable()->constrained('routes')->nullOnDelete();

            // Fechas
            $table->timestamp('departure_time');
            $table->timestamp('return_time')->nullable();

            // Kilometraje
            $table->decimal('km_departure', 10, 2);
            $table->decimal('km_return', 10, 2)->nullable();

            // Otros
            $table->text('observations')->nullable();
            $table->string('status')->default('in_progress');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
