<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class CreateAdminUser extends Command
{
    protected $signature = 'create:admin {email} {name} {password}';

    public function handle()
    {
        $data = $this->arguments();

        $data['role'] = 'admin';

        $user = User::create($data);

        $this->info('user created');

    }
}