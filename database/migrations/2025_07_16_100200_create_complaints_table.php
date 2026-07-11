<?php

use App\Enums\ComplainStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('complainable');
            $table->string('complain_number')->unique();
            $table->string('status')->default(ComplainStatusEnum::NEW);
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('reply')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complains');
    }
};
