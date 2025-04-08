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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("desc");
            $table->integer("priority");
            $table->enum("allow_multiple", ['yes', 'no'])->default('no');
            $table->integer('limit');
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->enum("coupon_type", ["nominal", "percentage"])->default("nominal");
            $table->string("voucher_code")->unique();
            $table->integer("discount");
            $table->enum("enable", ["yes", "no"])->default("yes");
            $table->foreignId('market_id')->constrained('markets')->cascadeOnDelete();
            $table->string('created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
