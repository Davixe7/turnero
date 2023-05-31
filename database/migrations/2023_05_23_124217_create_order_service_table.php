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
        Schema::create('order_service', function (Blueprint $table) {
            $table->timestamps();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');;
            $table->foreignId('service_id')->constrained()->onDelete('cascade');;
            $table->enum('state', ['pending', 'error', 'success'])->default('pending');
            $table->string('comment')->nullable();

            $table->foreignId('taken_by')->nullable();
            $table->datetime('taken_at')->nullable();
            $table->datetime('finished_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_service');
    }
};
