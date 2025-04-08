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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('is_default');
            $table->string('receiver')->nullable();
            $table->string('label');
            $table->string('address');
            $table->string('province');
            $table->string('city_name');
            $table->string('postcode');
            $table->decimal('lat', total: 18, places: 15);
            $table->decimal('lon', total: 18, places: 15);
            $table->string('shipping_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
