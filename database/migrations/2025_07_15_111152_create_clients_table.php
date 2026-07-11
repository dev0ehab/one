<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('country_code');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_blocked')->default(0);
            $table->boolean('is_notify')->default(1);
            $table->text('delete_reason')->nullable();
            $table->boolean('bookings_empty_banner_dismissed')->default(false);
            $table->decimal('points')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
