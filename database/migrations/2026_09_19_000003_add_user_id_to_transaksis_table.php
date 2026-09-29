<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('transaction_date')
                ->constrained('users')
                ->onDelete('set null');
        });

        // Isi user_id transaksi lama berdasarkan kecocokan nama kasir.
        DB::table('transaksis')
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $userId = DB::table('users')
                        ->where('name', $row->cashier_name)
                        ->value('id');

                    if ($userId) {
                        DB::table('transaksis')
                            ->where('id', $row->id)
                            ->update(['user_id' => $userId]);
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
