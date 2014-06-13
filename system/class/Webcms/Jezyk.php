<?php defined('SYSPATH') OR die('No direct script access.');

class Webcms_Jezyk {
	
	public static function lang() {
		
		$lang = DB::query(Database::SELECT, 'SELECT * FROM settings WHERE name="language"')->as_object(TRUE)->execute()->current();
		
		$session = Session::instance();
		
		if(empty($_GET['lang'])) {} else {
			$session->set('lang', $_GET['lang']);
			Request::instance()->redirect($_SERVER['REDIRECT_URL']);
		}
		
		if(!$session->get('lang')) {
			return $lang->value;
		} else {
			return $session->get('lang');
		}	
	}
	
	public static function laduj($name) {
	
	$wybierz = DB::query(Database::SELECT, 'SELECT * FROM tlumaczenia WHERE icon="'.$name.'" AND active = 1')->as_object(TRUE)->execute()->current();
	
	$db = DB::query(Database::SELECT, 'SELECT * FROM tlumaczenia_data WHERE lang_id='.$wybierz->id)->as_object(TRUE)->execute();
	foreach($db as $row) {
		$jezyk[$row->klucz] = $row->wartosc;
	}

	return $jezyk;
		
	}
	
	public static function get($name) {
	
		$tlumaczenie = Jezyk::laduj(Jezyk::lang());
				
		return $tlumaczenie[$name];
		
	}
	
	public static function reset_lang() {
		Session::instance()->delete("lang");
		Request::instance()->redirect('/');	
	}
	
}

?>