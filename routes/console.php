<?php

use App\User;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('admin:create {--name=} {--email=} {--password=}', function () {
    /** @var \Illuminate\Console\Command $this */
    $name = $this->option('name');
    $email = $this->option('email');
    $password = $this->option('password');
    $confirm = $this->option('password');

    while (!$name) {
        $name = $this->ask('Name');
    }
    while (!$email) {
        $email = $this->ask('Email');
    }
    while (!$password || !$confirm || $password !== $confirm) {
        if ($password && $confirm && $password !== $confirm) {
            $this->warn('Different passwords entered');
        }
        $password = $this->secret('Password');
        $confirm = $this->secret('Confirm');
    }

    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
    ]);

    $this->comment('Admin user created:');
    dump($user->toArray());
})->describe('Create an admin user');


Artisan::command('admin:clear', function () {
    /** @var \Illuminate\Console\Command $this */
    if ($this->confirm('Are you sure you want to delete all admins?')) {
        DB::table('users')->truncate();
        DB::table('password_resets')->truncate();
        $this->comment('All admins deleted');
    }
})->describe('Delete all users');
