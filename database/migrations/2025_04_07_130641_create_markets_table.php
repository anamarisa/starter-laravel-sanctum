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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('desc');
            $table->string('address_1');
            $table->string('city');
            $table->string('province');
            $table->integer('postcode');
            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->decimal('lat', total: 18, places: 15);
            $table->decimal('lon', total: 18, places: 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
