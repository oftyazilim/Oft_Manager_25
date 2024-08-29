<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emirlers', function (Blueprint $table) {
            $table->id();
            $table->string('HAT');
            $table->string('MAMUL');
            $table->integer('BOY');
            $table->float('MIKTAR');
            $table->integer('DURUM');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emirlers');

  }
};
