<?php

class Controller_dokumentacja extends Controller {
	
	public function __construct()	{}
	public function before()  {}
	public function after()  {}
	
	public function action_index() {
	$response = Response::factory();
	echo $response->body('Działa zajebiscie zajebista Dokumentacja :D');
	}
	
}

?>