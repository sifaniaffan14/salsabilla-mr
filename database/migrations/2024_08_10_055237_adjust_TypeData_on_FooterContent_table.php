<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('footercontent', function (Blueprint $table) 
        {
            $table->string('FooterContentTermAndCondition', 5000)->nullable()->change();
            $table->string('FooterContentPrivacyPolicy', 5000)->nullable()->change();
            $table->string('FooterContentFAQ', 5000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
