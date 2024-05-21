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
        Schema::create('item_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('item_id');
            $table
                ->foreign('item_id')
                ->references('id')
                ->on('items')
                ->cascadeOnDelete();

            $table->string('file_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_attachments');
    }
};