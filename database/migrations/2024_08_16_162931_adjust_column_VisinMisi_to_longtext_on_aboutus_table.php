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
        Schema::table('aboutus', function (Blueprint $table) {
            $table->longText('AboutUsVisi')->nullable()->change();
            $table->longText('AboutUsMisi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('aboutus', function (Blueprint $table) {
                $table->string('AboutUsVisi', 500)->nullable()->change();
                $table->string('AboutUsMisi', 500)->nullable()->change();
            });
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
    }
};
