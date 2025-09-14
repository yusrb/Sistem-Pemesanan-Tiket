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
        Schema::create('gerbongs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_gerbong', 20);
            $table->foreignId('kereta_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_kursi');
            $table->unique(['kereta_id', 'kode_gerbong']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gerbongs');
    }
};