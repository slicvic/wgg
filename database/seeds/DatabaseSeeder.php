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
            ['name' => 'facebook', 'label' => 'Facebook']
        ]);

        DB::table('event_types')->insert([
            ['name' => 'soccer', 'label' => 'Soccer']
        ]);

        DB::table('event_statuses')->insert([
            ['name' => 'active', 'label' => 'On'],
            ['name' => 'canceled', 'label' => 'Canceled']
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Victor Lantigua',
                'social_account_id' => '5128822',
                'social_account_type_id' => 1,
                'active' => 1
            ]
        ]);
    }
}
