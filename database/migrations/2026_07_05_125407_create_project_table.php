<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->string('no_project')->unique();
            $table->date('tanggal_project');
            $table->date('deadline_project');
            $table->enum('status_project', ['Proses', 'Selesai'])->default('Proses');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project');
    }
};