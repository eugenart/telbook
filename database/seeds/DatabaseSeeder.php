<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(BuildingsTableSeeder::class);
        $this->call(PeopleTableSeeder::class);
    }
}
