<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('orders', function (Blueprint $table) {
			$table->increments('id');
			$table->string('customer');
			$table->string('email');
			$table->integer('total');
			$table->text('order_contents');
			$table->enum('payment_type', ['Stripe', 'Paypal']);
			$table->enum('order_status', ['Pending', 'Paid', 'Complete']);
			$table->dateTime('order_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('orders');
	}

}
