<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // kalau kolom belum ada, tambahkan dulu sebagai binary
            if (!Schema::hasColumn('bookings', 'payment_screenshot')) {
                $table->binary('payment_screenshot')->nullable()->after('email');
            }
        });

        // lalu alter ke LONGBLOB supaya bisa simpan gambar besar
        DB::statement("ALTER TABLE bookings MODIFY payment_screenshot LONGBLOB NULL");
    }

    public function down(): void
    {
        // rollback ke varchar kalau di-down
        DB::statement("ALTER TABLE bookings MODIFY payment_screenshot VARCHAR(255) NULL");
    }
};
