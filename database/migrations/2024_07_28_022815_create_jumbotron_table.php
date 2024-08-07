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
		Schema::create('jumbotron', function(Blueprint $table) {
            $table->integer('JumbotronId', true);
            $table->string('JumbotronTittle', 50)->nullable();
            $table->string('JumbotronDescription', 150)->nullable();
            $table->string('JumbotronImage', 255)->nullable();
            $table->timestamp('JumbotronCreatedAt')->nullable();
            $table->timestamp('JumbotronUpdatedAt')->nullable();
            $table->timestamp('JumbotronDeletedAt')->nullable();
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
            Schema::dropIfExists('jumbotron');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
	}
};
