<?php

use App\Enums\ContactType;
use App\Enums\ContactStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('contactable');
            $table->string('name', 50)->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('title', 100)->nullable();
            $table->longText('message')->nullable();
            $table->string('image')->nullable();
            $table->string('reply')->nullable();
            $table->enum('type', ContactType::toArray())->default(ContactType::COMPLAINT);
            $table->enum('status', ContactStatus::toArray())->default(ContactStatus::NEW);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
