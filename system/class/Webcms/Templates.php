<?php defined('SYSPATH') OR die('No direct script access.');

class Webcms_Templates {
	
	public function init() {
		$templates = DB::query(Database::SELECT, 'SELECT * FROM settings WHERE name="templates"')->as_object(TRUE)->execute()->current();
		return $templates->value;	
	}
	
}

?>