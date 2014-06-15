<?php

class Controller_Home_Home extends Controller  {

	public function __construct() {
	$this->session = Session::instance();
    $this->auth = Auth::instance();
	$this->theme = Templates::init();
	Webcms::load(SYSPATH.'class/Constant.php');		
	}

	public function before()
    {
		$this->log = new Logs ( $_SERVER['DOCUMENT_ROOT']."/system/logs/log.csv" ,';' );
		$this->template = View::factory($this->theme.'/clean');
    }
	
	public function action_index() {

	$this->log->info('Otwieram Controller ' . Request::instance()->controller .' AKCJA '. Request::instance()->action .' KATALOG '. Request::instance()->directory);
	
	 //echo 'Page -> ' . $this->request->param('page');
         
	$title = "Tytuł Strony Głownej";
	$this->template->users = Auth::instance()->get_user();
	$this->template->headers = View::factory($this->theme.'/header')->bind('title',$title)->bind('uris',$aa);
	$this->template->content = "Coś";
	$this->template->footer = View::factory($this->theme.'/footer');
	echo $this->template;
	}
	
}





?>