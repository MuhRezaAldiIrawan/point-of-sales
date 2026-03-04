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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('alamat');
            $table->string('telepon_1');
            $table->string('telepon_2')->nullable();
            $table->string('email');
            $table->string('nama_direktur')->nullable();
            $table->string('no_hp')->nullable();
            $table->integer('stok_minimal')->default(10)->nullable();
            $table->integer('ppn')->default(11)->nullable();
            $table->text('keterangan_struk')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
