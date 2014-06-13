<?php defined('SYSPATH') or die('No direct script access.');

abstract class Webcms_Session {

	protected static $instances = array();
	
	public static $default = 'native';

	public static function instance($type = 'native', $id = NULL)
	{
		if ( ! isset(Session::$instances[$type]))
		{
			$config = Webcms::$config->load('Session')->get($type);
			//$a = $config->get($type);

			$class = 'Session_'.ucfirst($type);

			Session::$instances[$type] = $session = new $class($config, $id);

			register_shutdown_function(array($session, 'write'));
		}

		return Session::$instances[$type];
	}

	protected $_name = 'session';

	protected $_lifetime  = 0;

	protected $_encrypted = FALSE;

	protected $_data = array();

	protected $_destroyed = FALSE;

	protected function __construct(array $config = NULL, $id = NULL)
	{
		if (isset($config['name']))
		{
			$this->_name = (string) $config['name'];
		}

		if (isset($config['lifetime']))
		{
			$this->_lifetime = (int) $config['lifetime'];
		}

		if (isset($config['encrypted']))
		{
			if ($config['encrypted'] === TRUE)
			{
				$config['encrypted'] = 'default';
			}

			$this->_encrypted = $config['encrypted'];
		}

		$this->read($id);
	}

	public function __toString()
	{
		$data = serialize($this->_data);

		if ($this->_encrypted)
		{
			$data = Encrypt::instance($this->_encrypted)->encode($data);
		}
		else
		{
			$data = base64_encode($data);
		}

		return $data;
	}

	public function & as_array()
	{
		return $this->_data;
	}

	public function get($key, $default = NULL)
	{
		return array_key_exists($key, $this->_data) ? $this->_data[$key] : $default;
	}

	public function set($key, $value)
	{
		$this->_data[$key] = $value;

		return $this;
	}

	public function delete($key)
	{
		unset($this->_data[$key]);

		return $this;
	}

	public function read($id = NULL)
	{
		if (is_string($data = $this->_read($id)))
		{
			try
			{
				if ($this->_encrypted)
				{
					$data = Encrypt::instance($this->_encrypted)->decode($data);
				}
				else
				{
					$data = base64_decode($data);
				}

				$data = unserialize($data);
			}
			catch (Exception $e)
			{

			}
		}

		if (is_array($data))
		{
			$this->_data = $data;
		}
	}

	public function regenerate()
	{
		return $this->_regenerate();
	}

	public function write()
	{
		if (headers_sent() OR $this->_destroyed)
		{
			return FALSE;
		}

		$this->_data['last_active'] = time();

		try
		{
			return $this->_write();
		}
		catch (Exception $e)
		{
			Webcms::$log->add(Webcms::ERROR, Webcms::exception_text($e))->write();

			return FALSE;
		}
	}

	public function destroy()
	{
		if ($this->_destroyed === FALSE)
		{
			if ($this->_destroyed = $this->_destroy())
			{
				$this->_data = array();
			}
		}

		return $this->_destroyed;
	}

	abstract protected function _read($id = NULL);

	abstract protected function _regenerate();

	abstract protected function _write();

	abstract protected function _destroy();

}