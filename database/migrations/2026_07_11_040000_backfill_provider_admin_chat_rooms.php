<?php

use App\Models\Provider;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Provider::query()->each(function (Provider $provider) {
            $provider->ensureAdminChatRoom();
        });
    }

    public function down(): void
    {
        // Keep rooms; they may contain messages.
    }
};
