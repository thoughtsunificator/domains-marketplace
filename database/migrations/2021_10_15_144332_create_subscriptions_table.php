<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('subscriptions', function (Blueprint $table) {
			$table->increments('id');
			$table->string("plan");
			$table->integer("user_id");
			$table->integer("subscription_id");
			$table->string("gateway");
			$table->integer("subscription_date");
			$table->enum("subscription_status", ["Active", "Canceled"]);
			$table->double("subscription_price");
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('subscriptions');
	}

}
