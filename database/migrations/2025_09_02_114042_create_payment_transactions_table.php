<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('payment_getaway')->nullable();
            $table->string('transaction_id')->nullable();
            $table->integer('type')->default(0);
            $table->string('amount')->nullable();
            $table->string('currency_code')->nullable();
            $table->enum('status',['pending','completed','canceled','failed'])->default('pending');
            $table->json('getaway_response')->nullable();
            $table->morphs('trans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
};
