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
        Schema::create('board_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('board_id')->constrained()->cascadeOnDelete();
            $table->boolean('create_container')->default(false);
            $table->boolean('remove_container')->default(false);
            $table->boolean('create_item')->default(false);
            $table->boolean('remove_item')->default(false);
            $table->boolean('member_board_management')->default(false);
            $table->boolean('role_board_management')->default(false);
            $table->boolean('item_member_management')->default(false);
            $table->boolean('attachment_management')->default(false);
            $table->boolean('checklist_management')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_roles');
    }
};
