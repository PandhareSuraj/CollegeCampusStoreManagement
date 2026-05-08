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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')
                ->unique()
                ->constrained('stationary_requests')
                ->restrictOnDelete();
            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->restrictOnDelete();
            $table->string('order_number')->unique();
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->string('status')->default('pending');
            // Statuses: pending, confirmed, shipped, delivered, cancelled
            $table->integer('quantity_expected')->default(0);
            $table->integer('quantity_received')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for frequently queried columns
            $table->index('request_id');
            $table->index('vendor_id');
            $table->index('status');
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
