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
        Schema::create('workspace_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->boolean('create_board')->default(false);
            $table->boolean('update_board')->default(false);
            $table->boolean('delete_board')->default(false);
            $table->boolean('invite_user')->default(false);
            $table->boolean('remove_user')->default(false);
            $table->boolean('create_role')->default(false);
            $table->boolean('update_role')->default(false);
            $table->boolean('remove_role')->default(false);
            $table->boolean('assign_role')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_roles');
    }
};
