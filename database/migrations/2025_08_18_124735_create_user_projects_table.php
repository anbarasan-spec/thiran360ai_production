<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name', 255);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('project_price', 12, 2);
            // Your enum includes spaces — that’s allowed in MySQL
            $table->enum('project_status', ['pending', 'in progress', 'complete', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_projects');
    }
};
