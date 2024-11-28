<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Company;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('object_inspections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->date('executed_datetime')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('status_id')->nullable();
            $table->longtext('remark')->nullable();
            $table->longtext('document')->nullable();
            $table->longtext('certification')->nullable();
            $table->foreignId('elevator_id')->references('id')->on('elevators')->nullable();
            $table->foreignIdFor(Company::class)->constrained()->cascadeOnDelete();
            $table->string('schedule_run_token')->nullable();
            $table->string('inspection_company_id')->nullable();
            $table->string('nobo_number')->nullable();
            $table->string('if_match')->nullable();
            $table->string('type')->nullable();
            $table->string('external_uuid')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      
    }
};
