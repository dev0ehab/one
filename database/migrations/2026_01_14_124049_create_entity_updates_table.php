<?php

use App\Enums\UpdateTypeEnum;
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
        Schema::create('entity_updates', function (Blueprint $table) {
            $table->id();
            $table->morphs('updateable');
            $table->enum('type', UpdateTypeEnum::toArray())->default(UpdateTypeEnum::UPDATE);
            $table->json('data')->nullable();
            $table->boolean('is_approved')->nullable();
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
        Schema::dropIfExists('entity_updates');
    }
};
