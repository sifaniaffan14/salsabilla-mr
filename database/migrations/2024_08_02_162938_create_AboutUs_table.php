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
		Schema::create('aboutus', function(Blueprint $table) {
            $table->integer('AboutUsId', true);
            $table->string('AboutUsVisi', 50)->nullable();
            $table->string('AboutUsMisi', 50)->nullable();
            $table->json('AboutUsVisiImage')->nullable();
            $table->json('AboutUsMisiImage')->nullable();
            $table->timestamp('AboutUsCreatedAt')->nullable();
            $table->timestamp('AboutUsUpdatedAt')->nullable();
            $table->timestamp('AboutUsDeletedAt')->nullable();
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
            Schema::dropIfExists('aboutus');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
	}
};
