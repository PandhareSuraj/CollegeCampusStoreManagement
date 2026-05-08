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
        Schema::create('stationary_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')
                ->constrained('departments')
                ->restrictOnDelete();
            $table->foreignId('requested_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            // Statuses: pending, hod_approved, principal_approved, trust_approved, 
            // sent_to_provider, completed, rejected
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for frequently queried columns
            $table->index('department_id');
            $table->index('requested_by');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stationary_requests');
    }
};
