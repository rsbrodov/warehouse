<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();
        if(!empty($users)){
            $newUser = User::create([
                'name' => 'Admin',
                'email' => 'admin@cmsrider',
                'password' => Hash::make('nonah9kk'),
                'status' => 'ACTIVATED',
                'created_date' => Date('Y-m-d H:i:s'),
                'update_date' => Date('Y-m-d H:i:s'),
            ]);
            $newUser->assignRole('Admin');
        }
    }
}
