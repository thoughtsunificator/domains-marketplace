<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->string('token')->nullable();
			$table->string('headline')->nullable();
			$table->string('plan')->nullable();
			$table->integer('plan_expires')->nullable();
			$table->text('user_meta')->nullable();
			$table->string('plan_gateway')->nullable();
			$table->string('profilePic')->nullable();
			$table->boolean('is_activated')->default(false);
			$table->boolean('isAdmin')->default(false);
			$table->boolean('financingEnabled')->default(false);
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('users');
	}

}
