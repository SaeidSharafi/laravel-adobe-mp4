<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('adobe_connect_recordings', function (Blueprint $table) {
            $table->unsignedBigInteger('scoid')->primary();
            $table->string('foldername')->nullable();
            $table->string('url')->nullable();
            $table->string('datecreated')->nullable();
            $table->string('meetingurl')->nullable();
            $table->string('meetingname')->nullable();
            $table->string('recordingname')->nullable();
            $table->unsignedBigInteger('duration')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adobe_connect_recordings');
    }
};
