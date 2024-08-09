<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class socialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            SocialMedia::insert([
                [
                    'SocialMediaName' => "Instagram",
                    'SocialMediaCategory' => 1,
                    // 'SocialMediaURL' => "",
                ],
                [
                    'SocialMediaName' => "Facebook",
                    'SocialMediaCategory' => 1,
                    // 'SocialMediaURL' => "",
                ],
                [
                    'SocialMediaName' => "Tiktok",
                    'SocialMediaCategory' => 1,
                    // 'SocialMediaURL' => "",
                ],
                [
                    'SocialMediaName' => "Email",
                    'SocialMediaCategory' => 1,
                    // 'SocialMediaURL' => "",
                ],
                [
                    'SocialMediaName' => "Tokopedi",
                    'SocialMediaCategory' => 2,
                    // 'SocialMediaURL' => "",
                ],
                [
                    'SocialMediaName' => "Shopee",
                    'SocialMediaCategory' => 2,
                    // 'SocialMediaURL' => "",
                ],
            ]);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
