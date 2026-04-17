<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
         [
             'name' => 'biki',
             'password' => Hash::make('password'),
         ]  ,
            [
                'name' => 'rizkoy',
                'password' => Hash::make('bikitampan'),
            ]
        ];
        foreach ($data as $d) {
            User::create($d);
        }
    }
}
