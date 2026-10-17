<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// Index untuk pencarian/filter halaman publik. Aman di SQLite (lokal) maupun MySQL (server).
return new class extends Migration {
    public function up(): void {
        Schema::table('listings', function (Blueprint $t) {
            $t->index(['status', 'is_featured', 'created_at'], 'listings_public_idx');
            $t->index('transaction_type', 'listings_type_idx');
            $t->index('city', 'listings_city_idx');
        });
    }
    public function down(): void {
        Schema::table('listings', function (Blueprint $t) {
            $t->dropIndex('listings_public_idx'); $t->dropIndex('listings_type_idx'); $t->dropIndex('listings_city_idx');
        });
    }
};
