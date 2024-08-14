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
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
            $table->string('username', 50)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('images', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
            Schema::dropIfExists('users');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
	}
};
