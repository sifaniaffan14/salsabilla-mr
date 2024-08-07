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
		Schema::create('socialmedia', function(Blueprint $table) {
            $table->integer('SocialMediaId', true);
            $table->string('SocialMediaName', 50)->nullable();
            $table->integer('SocialMediaCategory', 50)->nullable()->comment('1: ContactUs; 2:Store');
            $table->string('SocialMediaURL', 100)->nullable();
            $table->timestamp('SocialMediaCreatedAt')->nullable();
            $table->timestamp('SocialMediaUpdatedAt')->nullable();
            $table->timestamp('SocialMediaDeletedAt')->nullable();
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
            Schema::dropIfExists('socialmedia');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
	}
};
