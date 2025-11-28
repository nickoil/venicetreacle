<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['id' => 1, 'title' => 'Sending'],
            ['id' => 2, 'title' => 'Illegal'],
            ['id' => 3, 'title' => 'Blacklisted'],
            ['id' => 4, 'title' => 'Sent'],
            ['id' => 5, 'title' => 'Unhandled'],
            ['id' => 6, 'title' => 'Bounced'],
            ['id' => 7, 'title' => 'Complaint'],
            ['id' => 8, 'title' => 'Delivered'],
        ];

        DB::table('email_statuses')->insert($statuses);
    }
}