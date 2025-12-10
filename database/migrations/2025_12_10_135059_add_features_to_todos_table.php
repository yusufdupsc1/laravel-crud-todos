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
        Schema::table('todos', function (Blueprint $table) {
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium')->after('description');
            $table->date('due_date')->nullable()->after('priority');
            $table->string('tags')->nullable()->after('due_date'); // We will store comma removed tags
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn(['priority', 'due_date', 'tags']);
        });
    }
};
