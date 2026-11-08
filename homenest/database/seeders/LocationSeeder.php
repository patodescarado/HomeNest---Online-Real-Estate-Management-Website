<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\District;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Hà Nội
        $hn = Province::create(['name' => 'Hà Nội']);
        $hn->districts()->createMany([
            ['name' => 'Ba Đình'],
            ['name' => 'Bắc Từ Liêm'],
            ['name' => 'Cầu Giấy'],
            ['name' => 'Đống Đa'],
            ['name' => 'Hà Đông'],
            ['name' => 'Hai Bà Trưng'],
            ['name' => 'Hoàn Kiếm'],
            ['name' => 'Hoàng Mai'],
            ['name' => 'Long Biên'],
            ['name' => 'Nam Từ Liêm'],
            ['name' => 'Tây Hồ'],
            ['name' => 'Thanh Xuân'],
            ['name' => 'Ba Vì'],
            ['name' => 'Chương Mỹ'],
            ['name' => 'Đan Phượng'],
            ['name' => 'Đông Anh'],
            ['name' => 'Gia Lâm'],
            ['name' => 'Hoài Đức'],
            ['name' => 'Mê Linh'],
            ['name' => 'Mỹ Đức'],
            ['name' => 'Phú Xuyên'],
            ['name' => 'Phúc Thọ'],
            ['name' => 'Quốc Oai'],
            ['name' => 'Sóc Sơn'],
            ['name' => 'Thạch Thất'],
            ['name' => 'Thanh Oai'],
            ['name' => 'Thanh Trì'],
            ['name' => 'Thường Tín'],
            ['name' => 'Ứng Hòa'],
            ['name' => 'Sơn Tây'],
        ]);

        // TP. Hồ Chí Minh
        $hcm = Province::create(['name' => 'TP. Hồ Chí Minh']);
        $hcm->districts()->createMany([
            ['name' => 'Quận 1'],
            ['name' => 'Quận 2'],
            ['name' => 'Quận 3'],
            ['name' => 'Quận 4'],
            ['name' => 'Quận 5'],
            ['name' => 'Quận 6'],
            ['name' => 'Quận 7'],
            ['name' => 'Quận 8'],
            ['name' => 'Quận 9'],
            ['name' => 'Quận 10'],
            ['name' => 'Quận 11'],
            ['name' => 'Quận 12'],
            ['name' => 'Bình Tân'],
            ['name' => 'Bình Thạnh'],
            ['name' => 'Gò Vấp'],
            ['name' => 'Phú Nhuận'],
            ['name' => 'Tân Bình'],
            ['name' => 'Tân Phú'],
            ['name' => 'Thủ Đức'],
            ['name' => 'Bình Chánh'],
            ['name' => 'Cần Giờ'],
            ['name' => 'Củ Chi'],
            ['name' => 'Hóc Môn'],
            ['name' => 'Nhà Bè'],
        ]);

        // Đà Nẵng
        $dn = Province::create(['name' => 'Đà Nẵng']);
        $dn->districts()->createMany([
            ['name' => 'Hải Châu'],
            ['name' => 'Thanh Khê'],
            ['name' => 'Sơn Trà'],
            ['name' => 'Ngũ Hành Sơn'],
            ['name' => 'Liên Chiểu'],
            ['name' => 'Cẩm Lệ'],
            ['name' => 'Hòa Vang'],
            ['name' => 'Hoàng Sa'],
        ]);

        // Hải Phòng
        $hp = Province::create(['name' => 'Hải Phòng']);
        $hp->districts()->createMany([
            ['name' => 'Hồng Bàng'],
            ['name' => 'Ngô Quyền'],
            ['name' => 'Lê Chân'],
            ['name' => 'Kiến An'],
            ['name' => 'Hải An'],
            ['name' => 'Đồ Sơn'],
            ['name' => 'Dương Kinh'],
            ['name' => 'An Dương'],
            ['name' => 'An Lão'],
            ['name' => 'Bạch Long Vĩ'],
            ['name' => 'Cát Hải'],
            ['name' => 'Kiến Thụy'],
            ['name' => 'Tiên Lãng'],
            ['name' => 'Thủy Nguyên'],
            ['name' => 'Vĩnh Bảo'],
        ]);

        // Quảng Ninh
        $qn = Province::create(['name' => 'Quảng Ninh']);
        $qn->districts()->createMany([
            ['name' => 'Hạ Long'],
            ['name' => 'Cẩm Phả'],
            ['name' => 'Móng Cái'],
            ['name' => 'Uông Bí'],
            ['name' => 'Đông Triều'],
            ['name' => 'Quảng Yên'],
            ['name' => 'Ba Chẽ'],
            ['name' => 'Bình Liêu'],
            ['name' => 'Cô Tô'],
            ['name' => 'Đầm Hà'],
            ['name' => 'Hải Hà'],
            ['name' => 'Tiên Yên'],
            ['name' => 'Vân Đồn'],
        ]);

        // Hải Dương
        $hd = Province::create(['name' => 'Hải Dương']);
        $hd->districts()->createMany([
            ['name' => 'Hải Dương'],
            ['name' => 'Chí Linh'],
            ['name' => 'Kinh Môn'],
            ['name' => 'Bình Giang'],
            ['name' => 'Cẩm Giàng'],
            ['name' => 'Gia Lộc'],
            ['name' => 'Kim Thành'],
            ['name' => 'Nam Sách'],
            ['name' => 'Ninh Giang'],
            ['name' => 'Thanh Hà'],
            ['name' => 'Thanh Miện'],
            ['name' => 'Tứ Kỳ'],
        ]);
    }
}
