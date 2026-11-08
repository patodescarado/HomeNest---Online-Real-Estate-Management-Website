<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $admin = User::forceCreate(['email' => 'admin@homenest.vn', 'password' => 'password', 'role' => 'admin']);
    $admin->profile()->create(['full_name' => 'Quản trị viên', 'phone'=>'0900000000', 'avatar'=> 'admin.jpg', 'bio'=>'I am admin']);
    
    $u1 = User::forceCreate(['email' => 'user1@example.com', 'password' => 'password']);
    $u1->profile()->create(['full_name' => 'Người dùng 1', 'phone' => '0911111111']);
    
    $u2 = User::forceCreate(['email' => 'user2@example.com', 'password' =>'password']);
    $u2->profile()->create(['full_name'=>'Người dùng 2', 'phone'=> '0922222222']);
    }
}
