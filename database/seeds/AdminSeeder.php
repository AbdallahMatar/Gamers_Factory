<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@game.com',
            'password' => Hash::make(123456),
            'gender' => 'Male',
            'birth_date' => '2001-04-17',
            'email_verified_at' => '2021-01-20',
            'status' => 'Active',
        ]);
    }
}
