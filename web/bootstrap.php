<?php
	@define(CONTROLLER, $_SERVER['DOCUMENT_ROOT']."/controller/", true);

	require SYSPATH.'class/Webcms/Core'.EXT;
	
	if (is_file(APPPATH.'class/Webcms'.EXT))
	{
		require APPPATH.'class/Webcms'.EXT;
	}
	else
	{
		require SYSPATH.'class/Webcms'.EXT;
	}

	
	spl_autoload_register(array('Webcms', 'auto_load'));
	ini_set('unserialize_callback_func', 'spl_autoload_call');
	
    $autoloader_dirs = array
    (
		"{$_SERVER['DOCUMENT_ROOT']}/system/class/logs.class.php"
    );
	
	foreach($autoloader_dirs as $inc) {
		require_once $inc;	
	}
	
	Cookie::$salt = 'Super Wypas';
	
	Webcms::init(array(
    'base_url' => '/', 
    'index_file' => FALSE,
	'errors' => TRUE
	));
	
	Webcms::modules(array(
	'dokumentacja'       => MODPATH.'dokumentacja',
	'auth'               => MODPATH.'auth',
	'database'           => MODPATH.'database',
	'orm'                => MODPATH.'orm',
	'pagination'         => MODPATH.'pagination'
	));

		// Homepage
		
		Route::set('error', 'error(/<action>)',array('args' => ".*") )->defaults(array(
			'directory'  => 'error',
			'controller' => 'error',
			'action'     => 'brak',
		));
		
		Route::set('admin', 'admin(/<action>(/<id>(/<param2>)))',array('id' => "[0-9]+",'param2' => "[0-9]+"))->defaults(array(
			'directory'  => 'admin',
			'controller' => 'admin',
			'action'     => 'index',
		));
		
		Route::set('login', 'login(/<action>)')->defaults(array(
			'directory'  => 'login',
			'controller' => 'login',
			'action'     => 'index',
		));

		Route::set('wykup', 'wykup(/<action>(/<param1>(/<param2>)))',array('param1' => "[a-zA-Z_/]+",'param2' => ".*"))->defaults(array(
			'directory'  => 'home',
			'controller' => 'wykup',
			'action'     => 'index',
		));
		
		Route::set('profil', 'profil(/<action>(/<id>(/<param2>)))',array('id' => "[0-9]+",'param2' => ".*"))->defaults(array(
			'directory'  => 'home',
			'controller' => 'profil',
			'action'     => 'index',
		));

		Route::set('aktualnosci', 'aktualnosci(/<id>)',array('id' => "[0-9]+"))->defaults(array(
			'directory'  => 'home',
			'controller' => 'home',
			'action'     => 'news',
		));
		
		Route::set('show', 'show(/<args>)',array('args' => ".*"))->defaults(array(
			'directory'  => 'home',
			'controller' => 'home',
			'action'     => 'show',
		));
		
		Route::set('promocje', 'promocje(/<args>)',array('args' => ".*"))->defaults(array(
			'directory'  => 'home',
			'controller' => 'home',
			'action'     => 'promocje',
		));
		
		Route::set('default', '(<action>(/<param1>(/<param2>))(/page<page>))',array('param1' => "[a-zA-Z_/]+",'param2' => ".*",'page'=>'[0-9]+'))->defaults(array(
			'directory'  => 'home',
			'controller' => 'home',
			'action'     => 'index',
		));
		
		
		$gzip = DB::query(Database::SELECT, 'SELECT * FROM settings WHERE name="gzip"')->as_object(TRUE)->execute()->current();
		if($gzip->value == 'true') {
			Gzip::init();	
		} else {
			Gzip::remove();
			ob_start();	
		}
		

$request = Request::instance();
try
{
	$request->execute();
}

catch( ReflectionException $e )
{
	header("Location: /");
	
/*			$template = View::factory('Webcms/error');
			$class   = get_class($e);
			$code    = $e->getCode();
			$message = $e->getMessage();
			$file    = $e->getFile();
			$line    = $e->getLine();
			$trace   = $e->getTrace();
			$template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
			$template->type = 'Error';
			$template->code = $code;
			$template->message = $message;
			$template->file = $file;
			$template->line = $line;
			$template->trace = $trace;
			echo $template;*/
	
	/*pr($e); die();
	$_SESSION['ex'] = '';
	// URL for new route
	$error_page = Route::get('error')->uri(array('action' => 'brak', 'id' => $request->uri()));
	$new_request = Request::factory($error_page);
	$new_request->execute();	
	$new_request->status = 404;
	$_SESSION['ex'] = $e; */
}

?>