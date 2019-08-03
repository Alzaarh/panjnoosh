<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    private $tables = [
        'users',
    ];

    public function run()
    {
        DB::statement('set foreign_key_checks = 0');

        foreach($this->tables as $table)
        {
            DB::table($table)->truncate();
        }
        // factory(App\Category::class, 10)->create();
        factory(App\Models\User::class, 1000)->create();
        // factory(App\Product::class, 100)->create();
        // $this->call('UsersTableSeeder');
    }
}
