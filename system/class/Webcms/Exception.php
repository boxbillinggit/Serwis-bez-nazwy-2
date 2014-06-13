<?php defined('SYSPATH') or die('No direct access');

class Webcms_Exception extends Exception {

	public function __construct($message, array $variables = NULL, $code = 0)
	{
		$message = strtr($message, array($variables));

		parent::__construct($message, $code);
	}

	public function __toString()
	{
		return Webcms::exception_text($this);
	}

}