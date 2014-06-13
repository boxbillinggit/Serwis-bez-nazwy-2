<?php 
    
class Exception extends Webcms_Exception {

	/**
	 * @var  array  PHP error code => human readable name
	 */
	public static $php_errors = array(
		E_ERROR              => 'Fatal Error',
		E_USER_ERROR         => 'User Error',
		E_PARSE              => 'Parse Error',
		E_WARNING            => 'Warning',
		E_USER_WARNING       => 'User Warning',
		E_STRICT             => 'Strict',
		E_NOTICE             => 'Notice',
		E_RECOVERABLE_ERROR  => 'Recoverable Error',
		E_DEPRECATED         => 'Deprecated',
	);

	/**
	 * @var  string  error rendering view
	 */
	public static $error_view = 'Webcms/error';

	/**
	 * @var  string  error view content type
	 */
	public static $error_view_content_type = 'text/html';

	/**
	 * Creates a new translated SException.
	 *
	 *     throw new SException('Something went terrible wrong, :user',
	 *         array(':user' => $user));
	 *
	 * @param   string          $message    error message
	 * @param   array           $variables  translation variables
	 * @param   integer|string  $code       the SException code
	 * @param   SException       $previous   Previous SException
	 * @return  void
	 */
	public function __construct($message = "", array $variables = NULL, $code = 0, SException $previous = NULL)
	{
		// Set the message
		$message = strtr($message, $variables);

		// Pass the message and integer code to the parent
		parent::__construct($message, (int) $code, $previous);

		// Save the unmodified code
		// @link http://bugs.php.net/39615
		$this->code = $code;
	}

	/**
	 * Magic object-to-string method.
	 *
	 *     echo $SException;
	 *
	 * @uses    SException::text
	 * @return  string
	 */
	public function __toString()
	{
		return SException::text($this);
	}

	/**
	 * Inline SException handler, displays the error message, source of the
	 * SException, and the stack trace of the error.
	 *
	 * @uses    SException::response
	 * @param   SException  $e
	 * @return  boolean
	 */
	public static function handler(SException $e)
	{
		$response = SException::_handler($e);

		// Send the response to the browser
		echo $response->send_headers()->body();
echo 'DUPDA!!!!!!!';
		exit(1);
	}

	/**
	 * SException handler, logs the SException and generates a Response object
	 * for display.
	 *
	 * @uses    SException::response
	 * @param   SException  $e
	 * @return  boolean
	 */
	public static function _handler(SException $e)
	{
		try
		{
			// Log the SException
			SException::log($e);

			// Generate the response
			$response = SException::response($e);

			return $response;
		}
		catch (SException $e)
		{
			/**
			 * Things are going *really* badly for us, We now have no choice
			 * but to bail. Hard.
			 */
			// Clean the output buffer if one exists
			ob_get_level() AND ob_clean();

			// Set the Status code to 500, and Content-Type to text/plain.
			header('Content-Type: text/plain; charset=', TRUE, 500);

			echo SException::text($e);

			exit(1);
		}
	}

	/**
	 * Logs an SException.
	 *
	 * @uses    SException::text
	 * @param   SException  $e
	 * @param   int        $level
	 * @return  void
	 */
	public static function log(SException $e, $level = Log::EMERGENCY)
	{
		if (is_object(Kohana::$log))
		{
			// Create a text version of the SException
			$error = SException::text($e);

			// Add this SException to the log
			Kohana::$log->add($level, $error, NULL, array('SException' => $e));

			// Make sure the logs are written
			Kohana::$log->write();
		}
	}

	/**
	 * Get a single line of text representing the SException:
	 *
	 * Error [ Code ]: Message ~ File [ Line ]
	 *
	 * @param   SException  $e
	 * @return  string
	 */
	public static function text(SException $e)
	{
		return sprintf('%s [ %s ]: %s ~ %s [ %d ]',
			get_class($e), $e->getCode(), strip_tags($e->getMessage()), $e->getFile(), $e->getLine());
	}

	/**
	 * Get a Response object representing the SException
	 *
	 * @uses    SException::text
	 * @param   SException  $e
	 * @return  Response
	 */
	public static function response(SException $e)
	{
		try
		{
			// Get the SException information
			$class   = get_class($e);
			$code    = $e->getCode();
			$message = $e->getMessage();
			$file    = $e->getFile();
			$line    = $e->getLine();
			$trace   = $e->getTrace();

			if ( ! headers_sent())
			{
				// Make sure the proper http header is sent
				$http_header_status = ($e instanceof HTTP_SException) ? $code : 500;
			}

			/**
			 * HTTP_SExceptions are constructed in the HTTP_SException::factory()
			 * method. We need to remove that entry from the trace and overwrite
			 * the variables from above.
			 */
			if ($e instanceof HTTP_SException AND $trace[0]['function'] == 'factory')
			{
				extract(array_shift($trace));
			}


			if ($e instanceof ErrorSException)
			{
				/**
				 * If XDebug is installed, and this is a fatal error,
				 * use XDebug to generate the stack trace
				 */
				if (function_exists('xdebug_get_function_stack') AND $code == E_ERROR)
				{
					$trace = array_slice(array_reverse(xdebug_get_function_stack()), 4);

					foreach ($trace as & $frame)
					{
						/**
						 * XDebug pre 2.1.1 doesn't currently set the call type key
						 * http://bugs.xdebug.org/view.php?id=695
						 */
						if ( ! isset($frame['type']))
						{
							$frame['type'] = '??';
						}

						// XDebug also has a different name for the parameters array
						if (isset($frame['params']) AND ! isset($frame['args']))
						{
							$frame['args'] = $frame['params'];
						}
					}
				}
				
				if (isset(SException::$php_errors[$code]))
				{
					// Use the human-readable error name
					$code = SException::$php_errors[$code];
				}
			}

			/**
			 * The stack trace becomes unmanageable inside PHPUnit.
			 *
			 * The error view ends up several GB in size, taking
			 * serveral minutes to render.
			 */
			if (defined('PHPUnit_MAIN_METHOD'))
			{
				$trace = array_slice($trace, 0, 2);
			}

			// Instantiate the error view.
			$view = View::factory(SException::$error_view, get_defined_vars());
			//echo SException::$error_view; 
			//echo '<pre>';
			//print_r(get_defined_vars());			
			// Prepare the response object.
			$response = Response::factory();

			// Set the response status
			$response->status(($e instanceof HTTP_SException) ? $e->getCode() : 500);
			
			// Set the response headers
			$response->headers('Content-Type', SException::$error_view_content_type.'; charset=UTF-8');

			// Set the response body
			$response->body();
		}
		catch (SException $e)
		{
			/**
			 * Things are going badly for us, Lets try to keep things under control by
			 * generating a simpler response object.
			 */
			$response = Response::factory();
			$response->status(500);
			$response->headers('Content-Type', 'text/plain');
			$response->body(SException::text($e));
		}

		return $response;
	}

}