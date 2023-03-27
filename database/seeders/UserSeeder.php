<?php

namespace Database\Seeders;

use App\Models\Admins\Admin;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::create([
        //     'firstname' => 'Christopher',
        //     'lastname' => 'Vistal',
        //     'middlename' => 'Platino',
        //     'username' => 'chris',
        //     'email' => 'christopher@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 1001,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'Edcel',
        //     'lastname' => 'Palomar',
        //     'middlename' => '',
        //     'username' => 'user1',
        //     'email' => 'user1@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 1001,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'Victor',
        //     'lastname' => 'Sumagang',
        //     'middlename' => '',
        //     'username' => 'user2',
        //     'email' => 'user2@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 1001,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);


        // User::create([
        //     'firstname' => 'J.Wald',
        //     'lastname' => 'Luna',
        //     'middlename' => '',
        //     'username' => 'user3',
        //     'email' => 'user3@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 1001,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'John Paul',
        //     'lastname' => 'Pradas',
        //     'middlename' => '',
        //     'username' => 'user4',
        //     'email' => 'user4@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 1001,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'Johnny Boy',
        //     'lastname' => 'Dua',
        //     'middlename' => '',
        //     'username' => 'user5',
        //     'email' => 'user5@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 1001,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'Winfox',
        //     'lastname' => 'Balbarino',
        //     'middlename' => '',
        //     'username' => 'user6',
        //     'email' => 'user6@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 1001,
        //     'office' => 1001,
        //     'role' => 'liaison',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'Christopher',
        //     'lastname' => 'Vistal',
        //     'middlename' => 'Platino',
        //     'username' => 'chris',
        //     'email' => 'christopher@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 2,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'Edcel',
        //     'lastname' => 'Palomar',
        //     'middlename' => '',
        //     'username' => 'user1',
        //     'email' => 'user1@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 3,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'Victor',
        //     'lastname' => 'Sumagang',
        //     'middlename' => '',
        //     'username' => 'user2',
        //     'email' => 'user2@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 4,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);


        // User::create([
        //     'firstname' => 'J.Wald',
        //     'lastname' => 'Luna',
        //     'middlename' => '',
        //     'username' => 'user3',
        //     'email' => 'user3@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 5,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'John Paul',
        //     'lastname' => 'Pradas',
        //     'middlename' => '',
        //     'username' => 'user4',
        //     'email' => 'user4@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 6,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'Johnny Boy',
        //     'lastname' => 'Dua',
        //     'middlename' => '',
        //     'username' => 'user5',
        //     'email' => 'user5@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 7,
        //     'office' => 1001,
        //     'role' => 'checker',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);

        // User::create([
        //     'firstname' => 'Winfox',
        //     'lastname' => 'Balbarino',
        //     'middlename' => '',
        //     'username' => 'user6',
        //     'email' => 'user6@yahoo.com',
        //     'phone_number' => '09709515936',
        //     'position' => 8,
        //     'office' => 1001,
        //     'role' => 'liaison',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'status'    =>  'approved'
        // ]);




        Admin::create([
            'name'  =>  'System Administrator',
            'email'  =>  'admin@yahoo.com',
            'username'  =>  'admin',
            'password'  =>  bcrypt('password'),
        ]);

    }
}