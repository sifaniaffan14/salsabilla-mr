<?php

namespace Database\Seeders;

use App\Models\FooterContent;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{
            FooterContent::insert([
                'FooterContentTermAndCondition' => "TESTING Term and Condition",
                'FooterContentPrivacyPolicy' => "TESTING Privacy Policy",
                'FooterContentFAQ' => "TESTING FAQ",
            ]);
        }catch(\Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}
