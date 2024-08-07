<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product', function(Blueprint $table) {
            $table->integer('ProductId', true)->autoIncrement('ProductId', true);
            $table->string('ProductName', 50)->nullable();
            $table->string('ProductDescription', 150)->nullable();
            $table->string('ProductJenis', 50)->nullable();
            $table->integer('ProductCategory')->nullable()->comment('1: Feminim; 2: Masculine; 3: Unisex; 4: Other');
            $table->tinyInteger('ProductIsBestSeller')->nullable()->comment('0: false; 1: true');
            $table->json('ProductImage')->nullable();
            $table->timestamp('ProductCreatedAt')->nullable();
            $table->timestamp('ProductUpdatedAt')->nullable();
            $table->timestamp('ProductDeletedAt')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		try {
            Schema::dropIfExists('product');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
	}
};
