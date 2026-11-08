<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PropertyType::insert([
    ['name' => 'Căn hộ chung cư', 'slug' => 'can-ho-chung-cu', 'for_sale' => true, 'for_rent' => true, 'sort_order' => 1],
    ['name'=> 'Nhà riêng', 'slug' => 'nha-rieng', 'for_sale' => true, 'for_rent' => true, 'sort_order' =>2],
    ['name'=>'Nhà mặt phố', 'slug'=>'nha_mat_pho', 'for_sale'=>true, 'for_rent'=>true, 'sort_order'=>3],
    ['name' => 'Biệt thự', 'slug' => 'biet-thu', 'for_sale' => true, 'for_rent'=> true, 'sort_order'=>4 ],
    ['name' => 'Đất', 'slug' => 'dat', 'for_sale' => true, 'for_rent' => false, 'sort_order' =>5 ],
    ['name' => 'Phòng trọ', 'slug' => 'phong-tro', 'for_sale' => false, 'for_rent' => true, 'sort_order' => 6],
    ['name' => 'Văn phòng', 'slug' => 'van-phong', 'for_sale' => false, 'for_rent' => true, 'sort_order' => 7],
    ['name' => 'Mặt bằng, cửa hàng', 'slug' => 'mat-bang', 'for_sale' => false, 'for_rent' => true, 'sort_order' => 8],
    ['name' => 'Bất động sản khác', 'slug' => 'bds-khac', 'for_sale' => true, 'for_rent' => true, 'sort_order' => 9]
]);
    }
}
