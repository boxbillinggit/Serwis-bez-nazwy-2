<?php defined('SYSPATH') OR die('No direct access allowed.');

class Webcms_Auth_File extends Auth {

	protected $users;

	public function __construct($config)
	{
		parent::__construct($config);

		// Load user list
				
		$this->users = empty($config['users']) ? array() : $config['users'];
	}

	public function _login($username, $password, $remember)
	{

		if (isset($this->users[$username]) AND $this->users[$username] === $password)
		{
			// Complete the login
			return $this->complete_login($username);
		}

		// Login failed
		return FALSE;
	}

	public function force_login($username)
	{
		// Complete the login
		return $this->complete_login($username);
	}

	public function password($username)
	{
		return isset($this->users[$username]) ? $this->users[$username] : FALSE;
	}

}