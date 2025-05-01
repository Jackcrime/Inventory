<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Elektronik',
                'description' => 'Barang-barang elektronik seperti komputer, printer, proyektor, dan sejenisnya.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Furnitur',
                'description' => 'Barang-barang furnitur seperti meja, kursi, lemari, rak, dan lainnya.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Alat Tulis Kantor (ATK)',
                'description' => 'Barang kebutuhan kantor seperti kertas, pena, staples, dan sejenisnya.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Kendaraan',
                'description' => 'Kendaraan operasional seperti mobil dinas, sepeda motor, dan lainnya.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Mesin',
                'description' => 'Peralatan atau mesin besar seperti genset, mesin fotocopy, dan lainnya.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Lainnya',
                'description' => 'Kategori lainnya yang tidak termasuk dalam kategori utama.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
