<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class userseeder extends Seeder
{
    /**
     * # 测试用户
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'phone' => '1234567890',
            'password' => Hash::make('password'),
            'phone_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        DB::table('users')->insert([
            'name' => 'admin2',
            'phone' => '0987654321',
            'password' => Hash::make('password'),
            'phone_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);

    }
}
