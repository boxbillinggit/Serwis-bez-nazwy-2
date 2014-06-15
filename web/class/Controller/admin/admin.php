<?php 
class Controller_Admin_admin extends Controller {

	public $template = 'admin/page';

	public function __construct() {
	$this->session = Session::instance();
    $this->auth = Auth::instance();
	$this->fb = Facebooks::instance();
	Webcms::load(SYSPATH.'class/Constant.php');
	
	//$this->menu = DB::query(Database::SELECT, 'SELECT * FROM menu_admin Where menu=0')->execute()->as_array();		
	
	}

	public function before()
    {
		$this->log = new Logs ( $_SERVER['DOCUMENT_ROOT']."/system/logs/log.csv" ,';' );
    }

public function action_index() {
	if ($this->auth->logged_in()) {
	$this->template = View::factory('admin/page');
	$this->template->title = 'Panel Administracyjny';
	$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
	$this->template->users = Auth::instance()->get_user();
	$this->template->content = 'Super Wypasiony content ';
	echo $this->template;
	} else {
	$this->template = View::factory('admin/login');
	$this->template->title = 'Panel Administracyjny';
	$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
	echo $this->template;
	}
}

public function action_gallery() 
{ 
	if ($this->auth->logged_in())
	{
		$this->template = View::factory('admin/page');
		$this->template->title = 'Panel Administracyjny';
		$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
		$this->template->users = Auth::instance()->get_user();
		$this->template->content = View::factory('admin/podstrony/gallery');
		
		if (!empty($_FILES))
		{
			$filename = Upload::save($_FILES, APPPATH . 'uploads/gallery');
						
			DB::query(Database::INSERT, DB::insert('galeria', array('name'))->values(array($filename)))->execute();			
			
		}
		
		$query = DB::query(Database::SELECT, 'SELECT * FROM galeria')->execute()->as_array();		
		
		$this->template->content->zdjecia = $query;
		
		echo $this->template;
		
	}
	else
	{
		$this->template = View::factory('admin/login');
		$this->template->title = 'Panel Administracyjny';
		$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
		echo $this->template;
	}
}

public function action_gallery_thumb($id = null, $param2 = null) 
{ 	
	if ($this->auth->logged_in())
	{
		/*		
		$this->template = View::factory('admin/page');
		$this->template->title = 'Panel Administracyjny';
		$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
		$this->template->users = Auth::instance()->get_user();
		$this->template->content = View::factory('admin/podstrony/gallery');
		
		if (!empty($_FILES))
		{
			$filename = Upload::save($_FILES, APPPATH . 'uploads/gallery');
			
			DB::query(Database::INSERT, DB::insert('galeria', array('name'))->values(array($filename)))->execute();			
			
		}
		
		$query = DB::query(Database::SELECT, 'SELECT * FROM galeria')->execute()->as_array();		
		
		$this->template->content->zdjecia = $query;
		
		echo $this->template;
		*/
		pr(Request::instance()->param('id'));
		pr(Request::instance()->param('param2'));
	}
	else
	{
		$this->template = View::factory('admin/login');
		$this->template->title = 'Panel Administracyjny';
		$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
		echo $this->template;
	}
}

public function action_podstrony() {
	if ($this->auth->logged_in('admin')) {
	$this->template = View::factory('admin/page');
	$this->template->title = 'Panel Administracyjny - Pod Strony';
	$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
	$this->template->users = Auth::instance()->get_user();
	//LADUJE DANE Z MYSQL 
	$query = DB::query(Database::SELECT, 'SELECT *, cms.id as id_cms FROM  `cms` LEFT JOIN  `menu` ON cms.seo = menu.link')->execute()->as_array();
	$menii = DB::query(Database::SELECT, 'SELECT * FROM menu WHERE sub_id=0 AND top=1')->execute()->as_array();
	$this->template->content = View::factory('admin/podstrony/index')->bind('podstrona',$query)->bind('menn',$menii);
	
	
	//KONCZE LADOWAĆ
	echo $this->template;
	$data = Request::current()->post();
	if(empty($data)) {} else {	
	$seo = str_replace(" ","_",$data['seo']);
	DB::query(Database::INSERT, DB::insert('menu', array('name','link','top','sub_id'))->values(array($data['title'],$seo,0,$data['sub_id'])))->execute();
	unset($data['sub_id']);
	DB::query(Database::INSERT, DB::insert('cms', array_keys($data))->values(array_values($data)))->execute();
	$this->log->info('Dodano Nową Podstrone O Tytule: '.$data['title']);
		Request::instance()->redirect("/admin/podstrony");
	}
	 
	
	} else {
		Request::instance()->redirect("/admin");
	}
}

public function action_podstrony_edit($id) {
	if ($this->auth->logged_in('admin')) {
	$this->template = View::factory('admin/page');
	$this->template->title = 'Panel Administracyjny - Pod Strony - Edycja';
	$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
	$this->template->users = Auth::instance()->get_user();
	//LADUJE DANE Z MYSQL 
	$query = DB::query(Database::SELECT, 'SELECT * FROM cms WHERE id="'.$id.'"')->as_object()->execute()->as_array();
	$query_seo = DB::query(Database::SELECT, 'SELECT * FROM menu WHERE link="'.$query[0]->seo.'"')->as_object()->execute()->as_array();
	$this->template->content = View::factory('admin/podstrony/edit')->bind('podstrona',$query)->bind('seo_id',$query_seo);
	
	//KONCZE LADOWAĆ
	echo $this->template;
	$data = Request::current()->post();
	if(empty($data)) {} else {
		DB::update('cms')->set(array('title' => $data['title'] ,  'text' => $data['text'] ,  'seo' => $data['seo'], 'comments_on_arts' => $data['comments'], 'rate_on_arts' => $data['rate'], 'logo' => $data['logo'] ))->where('id', '=', $id)->execute();
		$this->log->debug("Wyedytowano Wpis W Podstronach: <b>{$data['title']}</b>",'Podstrona');
		Request::instance()->redirect("/admin/podstrony_edit/".$id);
	}
	 
	
	} else {
		Request::instance()->redirect("/admin");
	}
}

public function action_podstrony_ajax() {
$kolejnosc = 1;
foreach($_POST['list'] as $klucz => $pozycja) {
	DB::update('cms')->set(array('position' => $kolejnosc))->where('id', '=', $pozycja)->execute();
	$cms = DB::query(Database::SELECT, 'SELECT * FROM cms WHERE id="'.$pozycja.'" AND seo LIKE \'%/show/%\';')->execute()->as_array();
	$seo = @$cms[0]['seo'];
	DB::update('menu')->set(array('position' => $kolejnosc))->where('link', '=', $seo)->execute();
	$kolejnosc = $kolejnosc +1;
}
die();
}

public function action_podstrony_delete($id) {
$this->log->warning('Usunięto Podstrone o id: '.$id);
DB::delete('cms')->where('id', '=', $id)->execute();
Request::instance()->redirect("/admin/podstrony");	
}
################################################ UŻYTKOWNICY
public function action_users() {
	if ($this->auth->logged_in('admin')) {
	$this->template = View::factory('admin/page');
	$this->template->title = 'Panel Administracyjny - Użytkownicy';
	$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
	$this->template->users = Auth::instance()->get_user();
	//LADUJE DANE Z MYSQL 
	$query = DB::query(Database::SELECT, 'SELECT * FROM users')->execute()->as_array();
	$role = DB::query(Database::SELECT, 'SELECT * FROM roles')->execute()->as_array();
	$this->template->content = View::factory('admin/users/index')->bind('users',$query)->bind('role',$role);
	
	//KONCZE LADOWAĆ
	echo $this->template;
	} else {
		Request::instance()->redirect("/admin");
	}
}

public function action_users_add() {
	if ($this->auth->logged_in('admin')) {
			$client = ORM::factory('user');
            $client->email = $_POST['email'];
            $client->username = $_POST['username'];
            $client->password = $_POST['password'];
            $client->save();
			foreach($_POST['role'] as $rola) {
			$role = ORM::factory('role',$rola);
			$client->add('roles',$role);
			$client->save();
			}
			Request::instance()->redirect("/admin/users");
	} else {
		Request::instance()->redirect("/admin");
	}
}

public function action_users_role_add() {
	if ($this->auth->logged_in('admin')) {
			$role = ORM::factory('role');
			$role->name = $_POST['name']; 
			$role->description = $_POST['description'];
			$role->save();
			Request::instance()->redirect("/admin/users");
	} else {
		Request::instance()->redirect("/admin");
	} 
	
}

public function action_users_edit($id) {
	if ($this->auth->logged_in('admin')) {
			if(empty($id)) {} else {
				$this->template = View::factory('admin/page');
				$this->template->title = 'Panel Administracyjny - Użytkownicy - Edycja Użytkownika o ID : '.$id;
				$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
				$this->template->users = Auth::instance()->get_user();
					$query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE id='.$id)->execute()->as_array();
					$role = DB::query(Database::SELECT, 'SELECT * FROM roles')->execute()->as_array();
					$role_users = DB::query(Database::SELECT, 'SELECT * FROM roles_users WHERE user_id='.$id)->execute()->as_array();
				$this->template->content = View::factory('admin/users/form')->bind('role',$role)->bind('users',$query)->bind('role_user',$role_users);
				echo $this->template;
				
				$data = Request::current()->post();
				if(empty($data)) {} else {
					$haslo = Auth::hash($_POST['password']);
					DB::update('users')->set(array('email' => $_POST['email'],'username' => $_POST['username'],'password'=> $haslo))->where('id', '=', $id)->execute();
					Request::instance()->redirect("/admin/logout");
				}
			}
	} else {
		Request::instance()->redirect("/admin");
	}
}

public function action_users_role_del($id) {
$this->log->warning('Usunięto Role o id: '.$id);
DB::delete('roles')->where('id', '=', $id)->execute();
Request::instance()->redirect("/admin/users");		
}

public function action_users_del($id) {
$this->log->warning('Usunięto Użytkownika o id: '.$id);
$this->log->warning('Usunięto Role Użytkownika o id: '.$id);
DB::delete('roles_users')->where('user_id', '=', $id)->execute();
DB::delete('users')->where('id', '=', $id)->execute();
Request::instance()->redirect("/admin/users");		
}

#############################################

public function action_settings() {
	if ($this->auth->logged_in('admin')) {
	$this->template = View::factory('admin/page');
	$this->template->title = 'Panel Administracyjny - Ustawienia';
	$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
	$this->template->users = Auth::instance()->get_user();
	//LADUJE DANE Z MYSQL 
	$this->template->content = View::factory('admin/settings/index');
	
	$this->log->info('Ustawienia ' . Request::instance()->controller .' AKCJA '. Request::instance()->action .' KATALOG '. Request::instance()->directory);
	
	//KONCZE LADOWAĆ
	echo $this->template;
	} else {
		Request::instance()->redirect("/admin");
	}
}


public function action_logs() {
	if ($this->auth->logged_in('admin')) {
	$this->template = View::factory('admin/page');
	$this->template->title = 'Panel Administracyjny - Logi Systemowe';
	$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
	$this->template->users = Auth::instance()->get_user();	
	$this->template->content = View::factory('admin/logs/index');
	$this->template->content->logs = DB::query(Database::SELECT, 'SELECT * FROM logs')->as_object()->execute();
	echo $this->template;
	} else {
		Request::instance()->redirect("/admin");
	}
}

public function action_loga_delete($id) {
$this->log->warning('Usunięto Logo o id: '.$id);
DB::delete('loga')->where('id', '=', $id)->execute();
Request::instance()->redirect("/admin/loga");	
}


public function action_login() {
	$this->auth->login(Request::instance()->post('username'), Auth::instance()->hash_password(Request::instance()->post('password')));

	$resp = array();
	$resp['submitted_data'] = $_POST;
	
	if ($this->auth->logged_in('admin')) {
	$login_status = 'success';
	$resp['redirect_url'] = '/admin';
	} else {
	$login_status = 'invalid';
	}
	
	$resp['login_status'] = $login_status;
	echo json_encode($resp);
}

public function action_reset_lang() {
	Jezyk::reset_lang();	
}

public function action_forgot() {
	$this->template = View::factory('admin/forgot');
	$this->template->title = 'Panel Administracyjny - Przypomnij Hasło';
	$this->template->uris = 'http://'.$_SERVER['HTTP_HOST'].'/web/view/admin/';
	echo $this->template;	
}

public function action_logout() {
	$this->auth->logout(true);
	Request::instance()->redirect("/admin");	
}

}
?>