<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback_media', function (Blueprint $table) {
            $table->foreignId('feedbacks_id')->constrained();
            $table->foreignId('media_id')->constrained();
            $table->primary(['feedbacks_id', 'media_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_media');
    }
};
