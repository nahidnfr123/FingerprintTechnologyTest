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
        Schema::create('user_tasks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('task_id')->nullable()->constrained();
            $table->primary(['user_id', 'task_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tasks');
//        Schema::table('user_tasks', function (Blueprint $table) {
//            $table->dropForeign(['user_id']);
//            $table->dropForeign(['task_id']);
//        });
    }
};
