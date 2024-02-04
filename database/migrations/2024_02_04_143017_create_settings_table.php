<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('component');
            $table->string('key');
            $table->string('value')->nullable();

            $table->primary(['component','key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};