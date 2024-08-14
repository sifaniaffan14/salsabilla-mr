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
		Schema::create('personal_access_token', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('tokenable_id')->nullable();
			$table->string('tokenable_type')->nullable();
			$table->string('name')->nullable();
			$table->string('token', 64)->unique();
			$table->text('abilities')->nullable();
			$table->timestamp('last_used_at')->nullable();
			$table->timestamp('expires_at')->nullable();
			$table->timestamp('created_at')->nullable();
			$table->timestamp('updated_at')->nullable();
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
            Schema::dropIfExists('personal_access_token');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
	}
};
