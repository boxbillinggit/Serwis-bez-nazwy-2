<?php

class Webcms_Google {
	public static $instances = array();
	
	public static function instance(array $config = NULL, $name = 'default')
	{
			if ($config === NULL)
			{
				// Load the configuration for this database
				$config = Webcms::$config->load('google')->$name;
			}

			if ( ! isset($config['ga_username']))
			{
				throw new Exception('Google Application Id not defined in '.$name.' configuration');
			}

			// Create the database connection instance
			new Google($config, $name);
	}
	
		// Instance name
	protected $_instance;

	// Configuration array
	protected $_config;
	
		/**
	 * Returns the database instance name.
	 *
	 * @return  string
	 */
	final public function __toString()
	{
		return $this->_instance;
	} 
	
	final protected function __construct(array $config, $name)
	{
		$this->_instance = $name;

		// Store the config locally
		$this->_config = $config;
		
		// Store the database instance
		Google::$instances[$name] = $this;
		
		if ( ! class_exists('gapi', FALSE))
		{
			// Load Facebook SDK 
			require_once Webcms::find_file('vendor', 'google/gapi');
		}
		
		define('ga_email',$this->_config['ga_username']);
		define('ga_password',$this->_config['ga_password']);
		define('ga_profile_id',$this->_config['ga_page']);
		
	}
	
	public static function page() {
	$configs = Webcms::$config->load('google');
		print_r($configs);
		
	}

	
}

?>