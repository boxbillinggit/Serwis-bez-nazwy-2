<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Webcms_Auth {

	protected static $_instance;

	public static function instance()
	{
		if ( ! isset(Auth::$_instance))
		{
			$config = Webcms::$config->load('auth');

			if ( ! $type = $config->get('driver'))
			{
				$type = 'ORM';
			}
			
			$class = 'Auth_'.ucfirst($type);

			Auth::$_instance = new $class($config);
		}

		return Auth::$_instance;
	}

	protected $_session;

	protected $_config;

	public function __construct($config = array())
	{
		$this->config = $config;

		$this->session = Session::instance();
	}

	abstract protected function _login($username, $password, $remember);

	abstract public function password($username);

	public function get_user($default = NULL)
	{
		return $this->session->get($this->config['session_key']);
	}

	public function login($username, $password, $remember = FALSE)
	{
		if (empty($password))
			return FALSE;
		return $this->_login($username, $password, $remember);
	}

	public function logout($destroy = FALSE, $logout_all = FALSE)
	{
		if ($destroy === TRUE)
		{
			$this->session->destroy();
		}
		else
		{
			$this->session->delete($this->config['session_key']);

			$this->session->regenerate();
		}

		return ! $this->logged_in();
	}

	public function logged_in($role = NULL)
	{
		return (bool) $this->session->get($this->config['session_key'], FALSE);
	}

	public function hash_password($password)
	{
		return $this->hash($password);
	}

	public function hash($str)
	{
		return hash_hmac(Webcms::$config->load('auth')->hash_method, $str, Webcms::$config->load('auth')->hash_key);
	}

	protected function complete_login($user)
	{
		$this->session->regenerate();

		$this->session->set($this->config['session_key'], $user);

		return TRUE;
	}

}