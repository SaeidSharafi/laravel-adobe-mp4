<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('adobe_connect_recording_queues', function (Blueprint $table) {
            $table->unsignedBigInteger('scoid')->primary();
            $table->foreign('scoid')->references('scoid')->on('adobe_connect_recordings')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->dateTime('datedownloaded')->nullable();
            $table->dateTime('datestarted')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adobe_connect_recording_queues');
    }
};
