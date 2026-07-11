<?php

use App\Models\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class, 'client_id')->constrained('clients')->cascadeOnDelete();
            $table->morphs('favoritable');
            $table->timestamps();

            $table->unique(['client_id', 'favoritable_id', 'favoritable_type'], 'unique_client_favorite');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
