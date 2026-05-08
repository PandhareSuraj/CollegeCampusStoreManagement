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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('contact_person')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('gst_number')->nullable();
            $table->text('bank_details')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('total_supplies', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for frequently queried columns
            $table->index('is_active');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
