<?php

abstract class Webcms_Facebooks {

/**
	 * @var  array  Facebook instances
	 */
	public static $instances = array();
	
	public function __call($name, $arguments)
	{
		if($name == 'api')
		{
			return $this->_facebook->api($arguments[0]);
		}
		else
		{
			return $this->_facebook->$name($arguments);
		}
	}
	
	
	/**
	 * Get a singleton Facebook instance. If configuration is not specified,
	 * it will be loaded from the facebook configuration file using the same
	 * group as the name.
	 *
	 * @param   string   instance name
	 * @param   array    configuration parameters
	 * @return  FBAPI
	 */
	public static function instance($name = 'default', array $config = NULL)
	{
		if ( ! isset(Facebooks::$instances[$name]))
		{
			if ($config === NULL)
			{
				// Load the configuration for this database
				$config = Webcms::$config->load('facebook')->$name;
			}

			if ( ! isset($config['app_id']))
			{
				throw new Exception('FBAPI Application Id not defined in '.$name.' configuration');
			}

			// Create the database connection instance
			new Facebooks($name, $config);
		}

		return Facebooks::$instances[$name];
	}
	
	// Instance name
	protected $_instance;

	// Configuration array
	protected $_config;
	
	// Facebook API class
	protected $_facebook;
	
	
	/**
	 * Stores the facebook configuration locally and names the instance.
	 *
	 * @return  void
	 */
	final protected function __construct($name,array $config)
	{
		
		//$configs = Webcms::$config->load('facebook')->$name;
	// Set the instance name
		$this->_instance = $name;

		// Store the config locally
		$this->_config = $config;
 
		// Store the database instance
		Facebooks::$instances[$name] = $this;

		if ( ! class_exists('Facebook', FALSE))
		{
			// Load Facebook SDK 
			require_once Webcms::find_file('vendor', 'facebook/src/facebook');
		}

		// Create our Application instance.
		$this->_facebook = new Facebook(array(
			'appId'  => $this->_config['app_id'],
			'secret' => $this->_config['app_secret'],
			'access_token' => $this->_config['access_token'],
			'cookie' => true,
			'scope' => 'publish_stream',
		));
	}
	
	/**
	 * Returns the database instance name.
	 *
	 * @return  string
	 */
	 public static function akces() {
		 $config = Webcms::$config->load('facebook')->default;
		 echo $config['access_token'];
	 }
	 
	final public function __toString()
	{
		return $this->_instance;
	} 
	
}

?>