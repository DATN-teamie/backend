<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');
            $table->string('container_id');
            $table
                ->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->cascadeOnDelete();
            $table->string('title');
            $table->integer('position');
            $table->unique(['container_id', 'position']);
            $table->text('description')->nullable();
            $table->string('checklist_name')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
