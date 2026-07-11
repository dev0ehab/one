<?php

use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class, 'client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('name');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('map_desc')->nullable();
            $table->foreignIdFor(Country::class)->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignIdFor(City::class)->nullable()->constrained('cities')->nullOnDelete();
            $table->boolean('is_main_location')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
