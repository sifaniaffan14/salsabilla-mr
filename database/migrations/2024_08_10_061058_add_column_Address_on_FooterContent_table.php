<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('footercontent', 'FooterContentAddress')) {
            Schema::table('footercontent', function (Blueprint $table) {
                $table->string('FooterContentAddress', 255)->nullable();
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('footercontent', function (Blueprint $table) {
            try {
                $table->dropColumn('FooterContentAddress');
            } catch (Exception $e) {
                Log::error($e->getMessage());
                throw new \RuntimeException($e->getMessage());
            }
        });
    }
};
