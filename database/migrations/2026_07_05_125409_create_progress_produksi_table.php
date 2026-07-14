<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('progress_produksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_po_id')->constrained('detail_po')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_progress');
            $table->enum('tahap_produksi', ['Tahap 1', 'Tahap 2', 'Tahap 3', 'Tahap 4', 'Finishing', 'QC', 'Masuk Gudang']);
            $table->integer('qty_progress');
            $table->text('dokumentasi')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status_progress', ['Proses', 'Selesai'])->default('Proses');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('progress_produksi');
    }
};