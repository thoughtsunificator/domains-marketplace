<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Options extends Model {

	public $timestamps = false;
	protected $fillable = ['name', 'value'];

	public static function update_option($name, $value) {
		$option = self::firstOrCreate(['name' => $name]);
		$option->value = $value;
		$option->save();
	}

	public static function get_option($name, $default = null) {
		$return = self::where('name', $name)->pluck('value')->first();
		if (!$return) return $default;
		return $return;
	}

	public static function delete_option($name) {
		$id = self::where('name', $name)->pluck('id')->first();
		if ($id) return self::destroy($id);
	}

	public static function first_from_list($comma_separated_list) {
		return reset(explode(',', $comma_separated_list));
	}

	public static function brand_name() {
		$site_title = self::get_option('site_title');
		return $site_title;
	}

}

