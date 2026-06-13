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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('company_name', 255);
            $table->string('contact_name', 255);
            $table->string('contact_title', 255);
            $table->string('contact_email', 255);
            $table->string('contact_number', 255);
            $table->string('address', 255);
            $table->string('postcode', 20);
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 100);
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
