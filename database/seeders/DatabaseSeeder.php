<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::insert([
            [
                'name' => 'Achmad',
                'email' => 'admin@mail.com',
                'password' => bcrypt('admin123')
            ],
            [
                'name' => 'Diyah',
                'email' => 'diyah@mail.com',
                'password' => bcrypt('admin123')
            ],
        ]);
    }
}
