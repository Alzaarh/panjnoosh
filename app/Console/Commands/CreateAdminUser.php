<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command {
    protected $signature = 'create:admin {username} {password}';
    public function handle() {
        $user = new User();
        $user->username = $this->argument('username');
        $user->password = Hash::make($this->argument('password'));
        $user->role = 'admin';
        $user->save();
        $this->info('User Created');
    }
}