<?php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DomainFactory extends Factory {

	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Domain::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition() {
		$name = $this->faker->unique()->domainName;
		return [
			"domain" => $name,
			"youtube_video_id" => "jNQXAC9IVRw",
			"keywords" => $this->faker->randomElement(["foo", "bar"]) . "," . $this->faker->randomElement(["hello", "world"]),
			"pricing" => $this->faker->numberBetween(1, 555430),
			"registrar" => $this->faker->randomElement(["foogle Domains", "hoDaddy.com"]),
			"reg_date" => Carbon::parse($this->faker->dateTimeBetween('-4 years', '+15 years'))->format('d-m-Y'),
			"description" => $this->faker->realText(1000),
			"domain_status" => $this->faker->randomElement(["AVAILABLE", "SOLD"]),
			"is_verified" => $this->faker->randomElement([false, true]),
			"is_approved" => $this->faker->randomElement([false, true]),
			"is_premium" => $this->faker->randomElement([false, true]),
			'domain_logo' => null,
			"category" => $this->faker->numberBetween(1, 5),
			"discount" => $this->faker->numberBetween(0, 50),
			"vendor_id" => $this->faker->numberBetween(1, 12),
			"end_datetime" => null,
			"start_datetime" => null,
			"days_difference" => "",
			"price_drop_value" => ""
		];
	}

}
