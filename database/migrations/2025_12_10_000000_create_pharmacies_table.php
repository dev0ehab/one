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
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->json('commercial_name');
            $table->json('desc')->nullable();
            $table->string('commercial_no');
            $table->string('name');
            $table->string('phone');
            $table->string('country_code');
            $table->string('email');
            $table->string('social_phone')->nullable();
            $table->string('social_email')->nullable();
            $table->string('whatsapp');
            $table->string('image')->nullable();
            $table->string('commercial_image')->nullable();
            $table->string('owner_image')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('lat')->nullable();
            $table->string('address')->nullable();
            $table->string('lng')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_num')->nullable();
            $table->string('iban')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->nullable()->default(null);
            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_notify')->default(true);
            $table->decimal('avg_rate', 3, 2)->default(0);
            $table->text('map_desc')->nullable();
            $table->text('reject_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
