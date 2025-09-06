<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // hapus kolom total_price
            if (Schema::hasColumn('bookings', 'total_price')) {
                $table->dropColumn('total_price');
            }

            // pastikan payment_screenshot ada dan tipe string (path file)
            if (!Schema::hasColumn('bookings', 'payment_screenshot')) {
                $table->string('payment_screenshot')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('total_price')->nullable();
            $table->dropColumn('payment_screenshot');
        });
    }
};
