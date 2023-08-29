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
        Schema::create('user_id_loggeds', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id_logged');
            $table->timestamps();

            $table->index('user_id_logged');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_id_loggeds');
    }
};
