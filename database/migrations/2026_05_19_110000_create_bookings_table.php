<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('providers')->cascadeOnDelete();
            $table->foreignId('provider_ad_id')
                ->nullable()
                ->constrained('provider_ads')
                ->nullOnDelete();
            $table->string('status')->default('new');
            $table->dateTime('scheduled_at');
            $table->string('service_title');
            $table->text('service_description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('address')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->decimal('additional_invoice_amount', 12, 2)->nullable();
            $table->string('additional_invoice_status')->nullable();
            $table->text('additional_invoice_reason')->nullable();
            $table->text('additional_invoice_client_reject_reason')->nullable();
            $table->timestamp('additional_invoice_payment_deadline')->nullable();
            $table->timestamp('additional_invoice_paid_at')->nullable();
            $table->string('additional_invoice_payment_method')->nullable();
            $table->decimal('additional_invoice_commission', 12, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('booking_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('image');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_images');
        Schema::dropIfExists('bookings');
    }
};
