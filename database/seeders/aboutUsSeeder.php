<?php

namespace Database\Seeders;

use App\Models\AboutUsSetting;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class aboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{
            AboutUsSetting::insert([
                'AboutUsVisi' => "TESTING VISI",
                'AboutUsMisi' => "TESTING MISI",
            ]);
        }catch(\Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}
