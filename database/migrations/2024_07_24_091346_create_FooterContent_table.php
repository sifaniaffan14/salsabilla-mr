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
		Schema::create('footercontent', function(Blueprint $table) {
			$table->integer('FooterContentId', true);
            $table->string('FooterContentTermAndCondition', 150)->nullable();
            $table->string('FooterContentPrivacyPolicy', 150)->nullable();
            $table->string('FooterContentFAQ', 50)->nullable();
            $table->timestamp('FooterContentCreatedAt')->nullable();
            $table->timestamp('FooterContentUpdatedAt')->nullable();
            $table->timestamp('FooterContentDeletedAt')->nullable();
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
            Schema::dropIfExists('footercontent');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
	}
};
