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
        Schema::create('hour_types', function (Blueprint $table) {
            $table->id(); 
            $table->string('name')->nullable();
            $table->boolean('is_active')->nullable()->default('1');
            $table->foreignId('company_id')->nullable()->constrained('companies');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hour_types');
    }
};
