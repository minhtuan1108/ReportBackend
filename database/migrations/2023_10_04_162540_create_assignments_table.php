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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id')->unsigned()->index()->nullable();
            $table->bigInteger('manager_id')->unsigned()->index()->nullable();
            $table->foreign('worker_id')->references('id')->on('users');
            $table->foreign('manager_id')->references('id')->on('users');
            $table->foreignId('reports_id')->constrained();
            $table->text('note');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
