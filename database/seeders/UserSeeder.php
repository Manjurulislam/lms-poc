<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name'     => 'Admin',
            'email'    => 'admin@mail.com',
            'admin' => true,
            'password' => Hash::make('123456'),
        ];

        User::create($data);
    }
}
