<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('offers', function (Blueprint $table) {
			$table->increments('id');
			$table->string("guest_user_id")->nullable();
			$table->string("phone_no")->nullable();
			$table->string("email")->nullable();
			$table->string("user_name")->nullable();
			$table->string("domain_id")->nullable();
			$table->text("remarks")->nullable();
			$table->string("offer_price")->nullable();
			$table->boolean("is_read_admin")->default(false);
			$table->boolean("is_read_seller")->default(false);
			$table->datetime("datetime")->nullable();
			$table->date("date")->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('offers');
	}

}
