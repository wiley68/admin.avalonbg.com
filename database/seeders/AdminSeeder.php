<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    public function run(): void
    {
        User::create([
            'name' => 'Админ Пиперков',
            'email' => 'home@avalonbg.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole('admin');
    }
}
