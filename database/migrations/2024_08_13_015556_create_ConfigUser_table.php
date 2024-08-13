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
		Schema::create('configuser', function(Blueprint $table) {
            $table->integer('UserId', true);
            $table->string('UserName', 50)->nullable();
            $table->string('UserPassword', 255)->nullable();
            $table->string('UserEmail', 50)->nullable();
            $table->timestamp('UserCreatedAt')->nullable();
            $table->timestamp('UserUpdatedAt')->nullable();
            $table->timestamp('UserDeletedAt')->nullable();
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
            Schema::dropIfExists('configuser');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
	}
};
