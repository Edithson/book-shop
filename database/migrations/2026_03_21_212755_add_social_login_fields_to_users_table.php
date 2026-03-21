<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change(); // Rend le mot de passe optionnel
            $table->string('provider')->nullable(); // ex: 'google', 'facebook'
            $table->string('provider_id')->nullable(); // L'ID unique fourni par Google/Facebook
            $table->string('provider_token')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
            $table->dropColumn(['provider', 'provider_id', 'provider_token']);
        });
    }
};
