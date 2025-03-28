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
        Schema::create('external_apilogs', function (Blueprint $table) {
            $table->id();
            $table->string('model')->nullable();
            $table->string('logitem')->nullable();
            $table->string('model_sub')->nullable();
            $table->string('schedule_run_token')->nullable();
            $table->string('status_id')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_apilogs');
    }
};
