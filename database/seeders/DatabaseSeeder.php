<?php
namespace Database\Seeders;
use App\Models\{Listing, User};
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        $a = User::create(['name'=>'Admin Warmad','email'=>'admin@warmad.test','password'=>'admin12345','role'=>'admin']);
        $rows = [
          ['Warung Madura 24 Jam Strategis Tepi Jalan Raya','jual',185000000,45000000,'Surabaya','Rungkut',60,48],
          ['Lapak Warung Madura Dekat Kampus','sewa',24000000,18000000,'Malang','Lowokwaru',30,24],
          ['Oper Kontrak Warung Madura Ramai Pelanggan','oper',65000000,32000000,'Sidoarjo','Waru',45,36],
          ['Ruko Mini Cocok Warung Madura Perumahan Baru','jual',320000000,null,'Gresik','Kebomas',84,70],
          ['Warung Madura Siap Operasional Lengkap Rak','oper',48000000,22000000,'Sumenep','Kota',36,30],
          ['Kios Pinggir Pasar Ramai Sewa Tahunan','sewa',18000000,null,'Pamekasan','Kota',20,18],
        ];
        foreach ($rows as $r) Listing::create([
          'user_id'=>$a->id,'title'=>$r[0],'transaction_type'=>$r[1],'price'=>$r[2],'monthly_omzet'=>$r[3],
          'province'=>'Jawa Timur','city'=>$r[4],'district'=>$r[5],'land_area'=>$r[6],'building_area'=>$r[7],
          'address'=>'Jl. Contoh No. '.rand(1,99).', '.$r[5].', '.$r[4],
          'description'=>"Lokasi strategis, lalu lintas pembeli tinggi, legalitas jelas dan siap operasional. Cocok untuk warung madura 24 jam.",
          'facilities'=>['Listrik','Air PDAM','Rak Display','Parkir'],'status'=>'active','is_featured'=>$r[2]>100000000,
        ]);
        $this->call(SampleImageSeeder::class);
    }
}
