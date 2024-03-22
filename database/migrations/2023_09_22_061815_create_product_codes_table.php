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
        Schema::create('product_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->unsigned();
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('product_code');
            $table->string('product_code_description', 255)->nullable();
            $table->unsignedBigInteger('insert_by')->unsigned();
            $table->foreign('insert_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_codes');
    }
};
