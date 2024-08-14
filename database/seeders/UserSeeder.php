<?php

namespace Database\Seeders;

use App\Models\ConfigUser;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{
            ConfigUser::insert([
                'username' => "admin",
                'password' => bcrypt("admin123"),
                'email' => "admin@gmail.com",
            ]);
        }catch(\Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}
