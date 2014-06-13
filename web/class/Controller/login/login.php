<?php

class Controller_login_login extends Controller  {

	public $template = 'home/page';

	public function __construct() {
	$this->session = Session::instance();
    $this->auth = Auth::instance();
	Webcms::load(SYSPATH.'class/Constant.php');		
	}
	
	public function before()
    {
		$this->log = new Logs ( $_SERVER['DOCUMENT_ROOT']."/system/logs/log.csv" ,';' );
		$this->url = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/home/';
		$this->template = View::factory('home/page')->bind('uris',$this->url);

	//MENU
	$this->menu = DB::query(Database::SELECT, 'SELECT * FROM menu WHERE top=1 AND sub_id=0')->execute()->as_array();	
    }
	
	public function action_index() {
	if ($this->auth->logged_in('user')) {
	Request::instance()->redirect("/");
	} else {
	Request::instance()->redirect("/");
	}
	}
	
	public function action_login() {	
	$this->auth->login(Request::instance()->post('username'), Auth::instance()->hash_password(Request::instance()->post('password')));
	if ($this->auth->logged_in('admin')) {
	Request::instance()->redirect("/admin");
	} else {
	Request::instance()->redirect("/");
	}
	
	if ($this->auth->logged_in('user')) {
	Request::instance()->redirect("/");
	} else {
	Request::instance()->redirect("/");
	}
	}
	
	public function action_gen() {
	echo Auth::instance()->hash_password("kurwka");
	}
	
	public function action_logout() {
	$this->auth->logout(true);
	Request::instance()->redirect("/");	
	}
	
}
	?>