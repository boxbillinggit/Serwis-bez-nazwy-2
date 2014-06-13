<?php

class Controller_Admin_dokumentacja extends Controller {
	
	public function __construct() {
		$this->session = Session::instance();
		$this->auth = Auth::instance();
		$this->fb = Facebooks::instance();
		Webcms::load(SYSPATH.'class/Constant.php');
	}
	
	public function before()  {
		
		if ($this->auth->logged_in()) {} else {
			Request::instance()->redirect("/admin");
		}
		
	}
	
	public function after()  {}
	
	public function action_index() {
	$response = Response::factory();
	echo $response->body('Panel adm Dokumentacji');
	}
	
}

?>