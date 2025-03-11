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
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('type_id')->change()->nullable();
            $table->foreignId('milestone_id')->after('type_id')->nullable();
            $table->foreignId('project_id')->after('milestone_id')->nullable();
            $table->foreignId('ticket_id')->after('project_id')->nullable();
            $table->foreignId('relation_id')->after('ticket_id')->nullable();
            $table->foreignId('workorder_id')->after('type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
};
