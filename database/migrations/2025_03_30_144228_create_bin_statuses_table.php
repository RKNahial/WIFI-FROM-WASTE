<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bin_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('bin_type');
            $table->boolean('is_full')->default(false);
            $table->integer('fill_percentage')->default(0);
            $table->timestamp('last_checked')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bin_statuses');
    }
};