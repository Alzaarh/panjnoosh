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
        factory(App\Category::class, 150)->create();
        factory(App\User::class, 1000)->create();
        // $this->call('UsersTableSeeder');
    }
}
