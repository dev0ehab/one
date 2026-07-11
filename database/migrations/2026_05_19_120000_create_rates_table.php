<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rates')) {
            return;
        }

        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rate');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique('booking_id');
            $table->index(['provider_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
