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
		Schema::create('productdetail', function(Blueprint $table) {
            $table->integer('ProductDetailId', true);
            $table->integer('ProductDetailProductId')->nullable()->index('ProductDetailProductId');
            $table->foreign('ProductDetailProductId', 'productdetail_ibfk_1')->references('ProductId')->on('product')->onDelete('restrict');
            $table->string('ProductDetailHarga', 50)->nullable();
            $table->string('ProductDetailUkuran', 50)->nullable();
            $table->timestamp('ProductDetailCreatedAt')->nullable();
            $table->timestamp('ProductDetailUpdatedAt')->nullable();
            $table->timestamp('ProductDetailDeletedAt')->nullable();
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
            Schema::dropIfExists('productdetail');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
	}
};
