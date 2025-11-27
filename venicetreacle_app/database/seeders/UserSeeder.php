<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* to build the password
        ❯ php artisan tinker
        Psy Shell v0.9.12 (PHP 7.4.27 — cli) by Justin Hileman
        >>> echo Hash::make('123456');
        $2y$10$JHK.2MTc9ORMmmlqoF.gg.SwDLnevVSj1oreHParu5PvcPEDOWqe6
        */

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@' . env('ROUTE_DOMAIN'),
            'password' => '$2y$12$7fHf7oZtfiHgkbrLaJ0ieeeTVNgd6W.9ZvmffdAZGufStpB778wom',
        ]);



    }
}
