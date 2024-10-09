<?php

namespace App\Console\Commands;

use App\Models\User;
use Hash;
use Illuminate\Console\Command;

class RegisterUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:register {name} {email} {password} {type}';

    protected $description = 'Register a new user';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = Hash::make($this->argument('password'));
        $type = $this->argument('type');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'type' => $type,
        ]);

        if ($user) {
            $this->info('User registered successfully');
        } else {
            $this->error('Failed to register user');
        }
    }
}
