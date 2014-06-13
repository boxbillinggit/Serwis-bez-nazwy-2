<?php

abstract class Webcms_Email {

	public static $instances = array();
	
	public static function instance($name = 'default', array $config = NULL)
	{
		if ( ! isset(Email::$instances[$name]))
		{
			if ($config === NULL)
			{
				// Load the configuration for this database
				$config = Webcms::$config->load('email')->$name;
			}

			if ( ! isset($config))
			{
				throw new Exception('Brak podanej domyślnej grupy '.$name.' w konfiguracji');
			}

			// Create the database connection instance
			new Email($name, $config);
		}

		return Email::$instances[$name];
	}
	
	// Instance name
	protected $_instance;

	// Configuration array
	protected $_config;
	
	// Email API class
	protected $_Email;
	
	protected $_to;
	protected $_subject;
	protected $_body;
	protected $_cc;
	protected $_bcc;
	protected $_od;
	
	final protected function __construct($name,array $config)
	{
		
		$this->_instance = $name;

		// Store the config locally
		$this->_config = $config;
 
		// Store the database instance
		Email::$instances[$name] = $this;

		// Create our Application instance.
		$this->_Email = array(
			'email1'  => $this->_config['support'],
			'email2' => $this->_config['webmaster'],
		);
	}
	
	public function send() {

	$do_kogo = $this->_Email[$this->_to];
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers .= 'To: Admin <'.$do_kogo.'>' . "\r\n";
	$headers .= 'From: '.$this->_subject.' <'.$this->_od.'>' . "\r\n";
	
	if(empty($this->_cc)) {} else {
	$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
	}
	
	if(empty($this->_bcc)) {} else {
	$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
	}

	try {
		mail($do_kogo, $this->_subject, $this->_body, $headers);
	}
	catch (Exception $e)
	{
		echo 'Exception caught: ' . $e->getMessage() . "\n";
	}
			
	}

	public function to($to) {
	$this->_to = $to;
	return $this->_to;	
	}
	
	public function subject($subject) {
	$this->_subject = $subject;
	return $this->_subject;	
	}
	
	public function cc($cc) {
	$this->_cc = $cc;
	return $this->_cc;	
	}
	
	public function bcc($bcc) {
	$this->_bcc = $bcc;
	return $this->_bcc;	
	}
	
	public function od($od) {
	$this->_od = $od;
	return $this->_od;	
	}
	
	public function body($body) {
	$this->_body = $body;
	return $this->_body;	
	}
	
	final public function __toString()
	{
		return $this->_instance;
	} 
	
}

?>