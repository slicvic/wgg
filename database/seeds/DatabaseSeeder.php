<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('social_account_types')->insert([
            ['name' => 'facebook', 'title' => 'Facebook']
        ]);

        DB::table('event_types')->insert([
            ['name' => 'soccer', 'title' => 'Soccer']
        ]);

        DB::table('event_statuses')->insert([
            ['name' => 'active', 'title' => 'Active'],
            ['name' => 'canceled', 'title' => 'Canceled']
        ]);
    }
}
