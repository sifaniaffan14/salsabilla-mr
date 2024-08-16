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
        Schema::table('product', function (Blueprint $table) 
        {
            $table->longText('ProductDescription')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
			Schema::table('product', function (Blueprint $table) {
				$table->varchar('ProductDescription', 150)->nullable()->change();
			});
		} catch (Exception $e) {
			Log::error($e->getMessage());
			throw new \RuntimeException($e->getMessage());
		}
    }
};
