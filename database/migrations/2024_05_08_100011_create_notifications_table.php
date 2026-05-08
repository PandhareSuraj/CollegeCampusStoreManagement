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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('type'); // email, system, sms
            $table->string('subject');
            $table->text('message');
            $table->string('action_type')->nullable(); // request_created, request_approved, etc.
            $table->foreignId('related_request_id')
                ->nullable()
                ->references('id')
                ->on('stationary_requests')
                ->nullOnDelete();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('status')->default('pending'); // pending, sent, failed
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            // Indexes for frequently queried columns
            $table->index('user_id');
            $table->index('is_read');
            $table->index('status');
            $table->index('related_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
