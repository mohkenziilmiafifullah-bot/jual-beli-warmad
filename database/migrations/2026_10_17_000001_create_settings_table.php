<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->text('value')->nullable();
        });
        // nilai awal = nomor dari config/.env
        DB::table('settings')->insert(['key' => 'admin_wa', 'value' => config('warmad.admin_wa')]);
    }
    public function down(): void { Schema::dropIfExists('settings'); }
};
