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
        Schema::create('detail_pemesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->constrained()->onDelete('cascade');
            $table->foreignId('penumpang_id')->constrained()->onDelete('cascade');
            $table->foreignId('gerbong_id')->constrained('gerbongs')->onDelete('restrict');
            $table->string('kode', 50)->unique();
            $table->enum('status', ['booked', 'checked_in', 'boarded', 'completed', 'cancelled'])->default('booked');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pemesanans');
    }
};