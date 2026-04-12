<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key sementara agar aman saat truncate (jika tabel berelasi)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Kosongkan tabel agar tidak terjadi duplikasi data jika seeder dijalankan berulang
        DB::table('banks')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        $banks = [
            [
                'name' => 'BCA', 
                'logo' => 'https://logo.clearbit.com/bca.co.id', 
                'is_active' => true, 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'Mandiri', 
                'logo' => 'https://logo.clearbit.com/bankmandiri.co.id', 
                'is_active' => true, 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'BNI', 
                'logo' => 'https://logo.clearbit.com/bni.co.id', 
                'is_active' => true, 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'BRI', 
                'logo' => 'https://logo.clearbit.com/bri.co.id', 
                'is_active' => true, 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'BSI', 
                'logo' => 'https://logo.clearbit.com/bankbsi.co.id', 
                'is_active' => true, 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'DANA', 
                'logo' => 'https://logo.clearbit.com/dana.id', 
                'is_active' => true, 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'OVO', 
                'logo' => 'https://logo.clearbit.com/ovo.id', 
                'is_active' => true, 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'GoPay', 
                'logo' => 'https://logo.clearbit.com/gopay.co.id', 
                'is_active' => true, 
                'created_at' => $now, 
                'updated_at' => $now
            ],
            [
                'name' => 'ShopeePay', 
                'logo' => 'https://logo.clearbit.com/shopee.co.id', 
                'is_active' => true, 
                'created_at' => $now, 
                'updated_at' => $now
            ],
        ];

        DB::table('banks')->insert($banks);
    }
}