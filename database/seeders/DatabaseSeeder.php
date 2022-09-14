<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name'          => 'Wawan Setiawan',
            'email'         => 'wawan.setiawan@ardgroup.com',
            'birth_date'    => '1990-01-10',
            'address'       => 'Asia Serasi No 100',
            'password'      => Hash::make('password')
        ]);

        \App\Models\User::factory()->create([
            'name'          => 'Joko Widodo',
            'email'         => 'joko.widodo@ardgroup.com',
            'birth_date'    => '1990-01-10',
            'address'       => 'Asia Serasi No 100',
            'password'      => Hash::make('password')
        ]);

        \App\Models\User::factory()->create([
            'name'          => 'Teguh Subiyantoro',
            'email'         => 'teguh.subiyantoro@ardgroup.com',
            'birth_date'    => '1991-02-10',
            'address'       => 'Jalan Pemekaran No 99',
            'password'      => Hash::make('password')
        ]);

        \App\Models\User::factory()->create([
            'name'          => 'Zulfa Ahmad',
            'email'         => 'zulfa.ahmad@ardgroup.com',
            'birth_date'    => '1992-03-10',
            'address'       => 'Dusun Pisang Rt 10 RW 20',
            'password'      => Hash::make('password')
        ]);
    }
}
