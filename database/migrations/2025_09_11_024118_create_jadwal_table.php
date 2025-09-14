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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kereta_id')->constrained()->onDelete('cascade');
            $table->string('stasiun_awal', 100);
            $table->string('stasiun_akhir', 100);
            $table->date('tanggal');
            $table->time('jam_berangkat');
            $table->time('jam_sampai');
            $table->decimal('harga', 10, 2);
            $table->index('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};