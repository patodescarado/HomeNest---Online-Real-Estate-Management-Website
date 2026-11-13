<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Property;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
        

// 1. Căn hộ 2 phòng ngủ tại Cầu Giấy - Hà Nội
$p = Property::create([
    'owner_id' => 2,
    'type_id' => 1,
    'district_id' => 3,
    'title' => 'Căn hộ 2 phòng ngủ tại Cầu Giấy',
    'description' => 'Căn hộ tầng 12, đầy đủ nội thất, gần các trường đại học và trung tâm thương mại.',
    'listing_kind' => 'sale',
    'price' => 3200000000,
    'area' => 68,
    'bedrooms' => 2,
    'bathrooms' => 2,
    'address' => '12 Trần Thái Tông, Cầu Giấy',

]);

$p->forceFill([
    'status' => 'approved',
    'expires_at' => now()->addDays(25),
])->save();


// 2. Phòng trọ có gác tại Đống Đa - Hà Nội
$p = Property::create([
    'owner_id' => 3,
    'type_id' => 6,
    'district_id' => 4,
    'title' => 'Phòng trọ có gác tại Đống Đa',
    'description' => 'Phòng trọ sạch sẽ, có gác xép, gần ga tàu điện và các trường đại học.',
    'listing_kind' => 'rent',
    'price' => 3500000,
    'area' => 25,
    'bedrooms' => 1,
    'bathrooms' => 1,
    'address' => '8 Tây Sơn, Đống Đa',

]);

$p->forceFill([
    'status' => 'approved',
    'expires_at' => now()->addDays(20),
])->save();


// 3. Căn hộ cao cấp tại Quận 7 - TP. Hồ Chí Minh
$p = Property::create([
    'owner_id' => 2,
    'type_id' => 1,
    'district_id' => 37,
    'title' => 'Căn hộ cao cấp tại Quận 7',
    'description' => 'Căn hộ hiện đại, đầy đủ tiện nghi, gần trung tâm thương mại Crescent Mall.',
    'listing_kind' => 'rent',
    'price' => 12000000,
    'area' => 55,
    'bedrooms' => 1,
    'bathrooms' => 1,
    'address' => '100 Nguyễn Thị Thập, Quận 7',

]);

$p->forceFill([
    'status' => 'approved',
    'expires_at' => now()->addDays(30),
])->save();


// 4. Nhà riêng 3 tầng tại Thủ Đức - TP. Hồ Chí Minh
$p = Property::create([
    'owner_id' => 3,
    'type_id' => 2,
    'district_id' => 48,
    'title' => 'Nhà riêng 3 tầng tại Thủ Đức',
    'description' => 'Nhà mới xây, thiết kế hiện đại, gần trường học và khu dân cư đông đúc.',
    'listing_kind' => 'sale',
    'price' => 4500000000,
    'area' => 90,
    'bedrooms' => 4,
    'bathrooms' => 3,
    'address' => '45 Võ Văn Ngân, Thành phố Thủ Đức',

]);

$p->forceFill([
    'status' => 'pending',
    'expires_at' => null,
])->save();


// 5. Nhà mặt phố tại Hải Châu - Đà Nẵng
$p = Property::create([
    'owner_id' => 2,
    'type_id' => 3,
    'district_id' => 52,
    'title' => 'Nhà mặt phố trung tâm Hải Châu',
    'description' => 'Nhà mặt tiền đường lớn, vị trí kinh doanh thuận lợi, gần cầu Rồng.',
    'listing_kind' => 'sale',
    'price' => 6800000000,
    'area' => 100,
    'bedrooms' => 3,
    'bathrooms' => 2,
    'address' => '120 Bạch Đằng, Hải Châu',

]);

$p->forceFill([
    'status' => 'approved',
    'expires_at' => now()->addDays(25),
])->save();
}
}