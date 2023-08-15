<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserFactory extends Factory {

	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = User::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition() {
		$name = $this->faker->unique()->name();
		return [
			'name' => $name,
			'email' => $this->faker->unique()->safeEmail(),
			'email_verified_at' => now(),
			'profilePic' => null,
			'is_activated' => $this->faker->randomElement([true, false]),
			'isAdmin' => false,
			'financingEnabled' => false,
			'headline' => $this->faker->unique()->sentence,
			'plan' => $this->faker->randomElement(["starter", "pro", "unlimited"]),
			'plan_expires' => $this->faker->randomElement([1729000837, 0]),
			'user_meta' => "",
			'plan_gateway' => "",
			'password' => "password", // password
			'created_at' => Carbon::now(),
			'remember_token' => "",
		];
	}

	/**
	 * Indicate that the model's email address should be unverified.
	 *
	 * @return \Illuminate\Database\Eloquent\Factories\Factory
	 */
	public function unverified() {
		return $this->state(function (array $attributes) {
			return [
				'email_verified_at' => null,
			];
		});
	}

}
