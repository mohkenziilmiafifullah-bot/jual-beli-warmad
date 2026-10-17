<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
/**
 * Untuk SERVER: hanya membuat/memperbarui akun admin (tanpa data demo, tanpa password bawaan).
 * Jalankan: php artisan db:seed --class=ProductionSeeder --force
 * (bisa juga non-interaktif lewat ADMIN_EMAIL, ADMIN_NAME, ADMIN_PASSWORD di .env sebelum config di-cache)
 */
class ProductionSeeder extends Seeder {
    public function run(): void {
        $email = env('ADMIN_EMAIL') ?: $this->command->ask('Email admin');
        $name  = env('ADMIN_NAME') ?: $this->command->ask('Nama admin', 'Admin Warmad');
        $pass  = env('ADMIN_PASSWORD') ?: $this->command->secret('Password admin (min. 8 karakter)');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen((string) $pass) < 8) {
            $this->command->error('Email tidak valid atau password kurang dari 8 karakter. Dibatalkan.');
            return;
        }
        User::updateOrCreate(['email' => $email], ['name' => $name, 'password' => $pass, 'role' => 'admin']);
        $this->command->info("Admin {$email} siap. Login di /admin");
    }
}
