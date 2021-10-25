<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('domains', function (Blueprint $table) {
			$table->increments('id');
			$table->string('domain');
			$table->integer('pricing');
			$table->string('registrar');
			$table->string('reg_date')->nullable();
			$table->integer('discount')->nullable();
			$table->text('youtube_video_id')->nullable();
			$table->text('keywords')->nullable();
			$table->integer('vendor_id');
			$table->boolean('domain_history')->default(false);
			$table->string('domain_logo')->nullable();
			$table->integer('category')->default('1');
			$table->boolean('is_verified')->default(false);
			$table->boolean('is_approved')->default(false);
			$table->boolean('is_premium')->default(false);
			$table->boolean('price_drop')->default(false);
			$table->string('price_drop_value')->nullable();
			$table->string('days_difference')->nullable();
			$table->dateTime('start_datetime')->nullable();
			$table->dateTime('end_datetime')->nullable();
			$table->text('description')->nullable();
			$table->enum('domain_status', ['AVAILABLE', 'SOLD']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('domains');
	}

}
