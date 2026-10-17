<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', fn (Blueprint $t) => $t->string('role')->default('user')->after('email'));
        Schema::create('listings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('title'); $t->string('slug')->unique();
            $t->string('transaction_type')->default('jual');
            $t->unsignedBigInteger('price')->default(0);
            $t->unsignedBigInteger('monthly_omzet')->nullable();
            $t->unsignedInteger('land_area')->nullable();
            $t->unsignedInteger('building_area')->nullable();
            $t->text('description')->nullable();
            $t->json('facilities')->nullable(); $t->json('photos')->nullable();
            $t->string('province')->nullable(); $t->string('city')->nullable(); $t->string('district')->nullable();
            $t->text('address')->nullable();
            $t->decimal('latitude', 10, 7)->nullable(); $t->decimal('longitude', 10, 7)->nullable();
            $t->string('whatsapp')->nullable();
            $t->string('status')->default('pending');
            $t->boolean('is_featured')->default(false);
            $t->unsignedInteger('views')->default(0);
            $t->timestamps();
        });
        Schema::create('inquiries', function (Blueprint $t) {
            $t->id();
            $t->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $t->string('name'); $t->string('phone'); $t->text('message');
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('inquiries'); Schema::dropIfExists('listings');
    }
};
