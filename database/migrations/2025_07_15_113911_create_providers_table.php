<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('owner_name')->nullable();
            $table->string('trade_name');
            $table->string('phone');
            $table->string('password')->nullable();
            $table->string('country_code');
            $table->string('email');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('image')->nullable();
            $table->string('national_id')->nullable();
            $table->string('commercial_register_num')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_num')->nullable();
            $table->string('iban')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_approved')->nullable()->default(null);
            $table->boolean('has_booking_service')->default(false);
            $table->timestamp('booking_service_expires_at')->nullable();
            $table->unsignedSmallInteger('service_duration_minutes')->nullable();
            $table->text('booking_notes')->nullable();
            $table->unsignedSmallInteger('booking_reminder_minutes')->nullable();
            $table->boolean('show_in_offers')->default(false);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->boolean('is_notify')->default(true);
            $table->decimal('avg_rate', 3, 2)->default(0);
            $table->text('rejection_reason')->nullable();
            $table->text('map_desc')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->json('banners')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
