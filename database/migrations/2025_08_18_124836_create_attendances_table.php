<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->index();
            $table->date('date')->index();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('total_hours_per_day', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendances');
    }
};
