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
        Schema::table('detail_daftar_hargas', function (Blueprint $table) {
            if (Schema::hasColumn('detail_daftar_hargas', 'harga_beli')) {
                $table->dropColumn('harga_beli');
            }

            if (!Schema::hasColumn('detail_daftar_hargas', 'satuan_id')) {
                $table->unsignedBigInteger('satuan_id')->nullable()->after('harga_jual');

                $table->foreign('satuan_id')->references('id')->on('satuans')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_daftar_hargas', function (Blueprint $table) {
            if (Schema::hasColumn('detail_daftar_hargas', 'satuan_id')) {
                $table->dropForeign(['satuan_id']);
                $table->dropColumn('satuan_id');
            }
            if (!Schema::hasColumn('detail_daftar_hargas', 'harga_beli')) {
                $table->decimal('harga_beli', 15, 2)->nullable();
            }
        });
    }
};
