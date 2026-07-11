<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->boolean('private')->default(true);
            $table->string('type')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->nullableMorphs('createable');
            $table->unsignedBigInteger('last_message_id')->default(0);
            $table->timestamps();
        });

        Schema::create('room_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->morphs('memberable');
            $table->timestamps();

            $table->unique(['room_id', 'memberable_id', 'memberable_type'], 'room_member_unique');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->morphs('senderable');
            $table->text('body');
            $table->string('type')->default('text');
            $table->string('duration')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('message_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('message_id')->constrained('messages')->cascadeOnDelete();
            $table->morphs('userable');
            $table->boolean('is_seen')->default(false);
            $table->boolean('is_sender')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_notifications');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('room_members');
        Schema::dropIfExists('rooms');
    }
};
