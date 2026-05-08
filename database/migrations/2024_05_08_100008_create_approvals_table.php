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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')
                ->constrained('stationary_requests')
                ->cascadeOnDelete();
            $table->foreignId('approved_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->string('role'); // hod, principal, trust_head, admin
            $table->string('status'); // approved, rejected
            $table->text('remarks')->nullable();
            $table->integer('approval_level'); // 1=HOD, 2=PRINCIPAL, 3=TRUST_HEAD, 4=ADMIN
            $table->timestamps();
            
            // Indexes for frequently queried columns
            $table->index('request_id');
            $table->index('approved_by');
            $table->index('status');
            $table->index('approval_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
