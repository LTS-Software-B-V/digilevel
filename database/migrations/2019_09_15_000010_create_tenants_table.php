<?php

declare (strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('database')->nullable();
            $table->string('logo')->nullable();
            $table->string('color1')->nullable();
            $table->string('color2')->nullable();
            $table->string('subscription_id')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('place')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
