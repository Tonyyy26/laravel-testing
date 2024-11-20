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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('todo_list_id')
                ->constrained('todo_lists', 'id')
                ->onDelete('cascade');
            $table->text('description')->nullable();
            // $table->foreignId('label_id')
            //     ->constrained()
            //     ->onDelete('cascade');
            $table->string('status')->default('not_started');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
