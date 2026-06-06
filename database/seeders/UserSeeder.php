<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ja_JP');

        $departments = ['営業部', '開発部', '人事部', '総務部', '経理部', 'マーケティング部', '企画部', 'カスタマーサポート部'];
        $employmentTypes = ['正社員', '正社員', '正社員', '契約社員', '契約社員', 'アルバイト', '外部の人'];

        for ($i = 1; $i <= 100; $i++) {
            $isRetired = $faker->boolean(10); // 10%の確率で退職済み

            User::firstOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name'            => $faker->name(),
                    'email'           => "user{$i}@example.com",
                    'password'        => Hash::make('password'),
                    'department'      => $faker->randomElement($departments),
                    'employment_type' => $faker->randomElement($employmentTypes),
                    'is_retired'      => $isRetired,
                    'comment'         => $faker->boolean(30) ? $faker->realText(30) : '',
                ]
            );
        }

        $this->command->info('100人のユーザーを追加しました。');
    }
}
