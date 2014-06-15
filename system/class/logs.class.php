<?php
class Logs {

	// Name of the file where the message logs will be appended.
	private $LOGFILENAME;
	
	// Define the separator for the fields. Default is comma (,).
	private $SEPARATOR;

	// headers
	private $HEADERS;
	
	// Default tag.
	const DEFAULT_TAG = '--';

		
	// CONSTRUCTOR
	function Logs($logfilename = './_Logs-1.2.log.csv', $separator = ',') {
		$this->LOGFILENAME = $logfilename;
		$this->SEPARATOR = $separator;
		$this->HEADERS =
			'DATETIME' . $this->SEPARATOR . 
			'ERRORLEVEL' . $this->SEPARATOR .
			'TAG' . $this->SEPARATOR .
			'VALUE' . $this->SEPARATOR .
			'LINE' . $this->SEPARATOR .
			'FILE';
	}
	
	
	// Private method that will write the text logs into the $LOGFILENAME.
	private function log($errorlevel = 'INFO', $value = '', $tag) {
	
		$datetime = date("Y-m-d H:i:s");
		if (!file_exists($this->LOGFILENAME)) {
			$headers = $this->HEADERS . "\n";
		}
		
		$debugBacktrace = debug_backtrace();
		$line = $debugBacktrace[1]['line'];
		$file = $debugBacktrace[1]['file'];
		
		$entry = array("DATETIME" => $datetime, "ERRORLEVEL" => $errorlevel, "TAG" => $tag, "VALUE" => $value,"LINE" => $line,"FILE" => $file);
		
		DB::query(Database::INSERT, DB::insert('logs', array_keys($entry))->values(array_values($entry)))->execute();
		
	}
	
	
	// Function to write not technical INFOrmation messages that will be written into $LOGFILENAME.
	function info($value = '', $tag = self::DEFAULT_TAG) {
	
		self::log('INFO', $value, $tag);
	}
	
	
	// Function to write WARNING messages that will be written into $LOGFILENAME.
	// These messages are non-fatal errors, so, the script will work properly even
	// if WARNING errors appears, but this is a thing that you must ponderate about.
	function warning($value = '', $tag = self::DEFAULT_TAG) {
	
		self::log('WARNING', $value, $tag);
	}


	// Function to write ERROR messages that will be written into $LOGFILENAME.
	// These messages are fatal errors. Your script will NOT work properly if an ERROR happens, right?
	function error($value = '', $tag = self::DEFAULT_TAG) {
	
		self::log('ERROR', $value, $tag);
	}

	// Function to write DEBUG messages that will be written into $LOGFILENAME.
	// DEBUG messages are highly technical issues, like an SQL query or result of it.
	function debug($value = '', $tag = self::DEFAULT_TAG) {
	
		self::log('DEBUG', $value, $tag);
	}
	
}

?>