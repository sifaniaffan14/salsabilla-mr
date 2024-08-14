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
		Schema::table('users', function (Blueprint $table) {
			$table->renameColumn('name', 'username');
			$table->integer('images')->nullable();
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
			Schema::table('users', function (Blueprint $table) {
				$table->dropColumn('images');
				$table->dropColumn('deleted_at');
				$table->renameColumn('username', 'name');
			});
		} catch (Exception $e) {
			Log::error($e->getMessage());
			throw new \RuntimeException($e->getMessage());
		}
	}
};
