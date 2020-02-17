<?php

use App\Model\User\Wallet;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'first_name'=>'admin',
            'last_name'=>'',
            'email'=>'admin@yopmail.com',
            'type'=>USER_ROLE_ADMIN,
            'status'=>STATUS_SUCCESS,
            'phone'=>'8888888888888',
            'is_verified'=>1,
            'password'=>\Illuminate\Support\Facades\Hash::make('12345'),
        ]);

        User::insert([
            'first_name'=>'user',
            'last_name'=>'',
            'email'=>'user@yopmail.com',
            'type'=>USER_ROLE_USER,
            'status'=>STATUS_SUCCESS,
            'phone'=>'8888888888888',
            'is_verified'=>1,
            'password'=>\Illuminate\Support\Facades\Hash::make('12345'),
        ]);
        Wallet::insert([
            'user_id'=>2,
            'name'=>'Default Wallet',
            'status'=>STATUS_SUCCESS,
            'is_primary'=>'1',
            'balance'=>0

        ]);
        Wallet::insert([
            'user_id'=>1,
            'name'=>'Default Wallet',
            'status'=>STATUS_SUCCESS,
            'is_primary'=>'1',
            'balance'=>0

        ]);
    }
}
