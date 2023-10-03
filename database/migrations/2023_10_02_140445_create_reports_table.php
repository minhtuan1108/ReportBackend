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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->tinyText('title');
            $table->text('description');
            $table->string('location_api');
            $table->string('location_text');
            $table->set('status', ['COMPLETE', 'SENT', 'DRAFT', 'PROCESS', 'IGNORE']);
            $table->foreignId('users_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
