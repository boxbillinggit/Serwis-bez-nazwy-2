<?php 



class Webcms_Request implements HTTP_Request {



	// HTTP status codes and messages

	public static $messages = array(
		// Informational 1xx
		100 => 'Continue',
		101 => 'Switching Protocols',

		// Success 2xx
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',

	// Redirection 3xx
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found', // 1.1
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
	// 306 is deprecated but reserved
		307 => 'Temporary Redirect',

		// Client Error 4xx
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',

		// Server Error 5xx
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		509 => 'Bandwidth Limit Exceeded'
	);



	/**

	 * @var  string  method: GET, POST, PUT, DELETE, etc

	 */

	public static $method = 'GET';

	/**
 * @var  string  protocol: http, https, ftp, cli, etc
	 */
	//public static $protocol = 'http';
	protected $_protocol;
	public static $protocol = FALSE;

	/**
	 * @var  string  referring URL
	 */

	public static $referrer;

	/**
	 * @var  string  client user agent
	 */

	public static $user_agent = '';

	/**
	 * @var  string  client IP address
	 */

	public static $client_ip = '0.0.0.0';

	/**
	 * @var  boolean  AJAX-generated request
	 */

	public static $is_ajax = FALSE;
	//public static $current;
	public static $current;

	/**
	 * @var array    query parameters
	 */
	protected $_get = array();

	/**
	 * @var array    post parameters
	 */
	protected $_post = array();

	/**
	 * Main request singleton instance. If no URI is provided, the URI will
	 * be automatically detected using PATH_INFO, REQUEST_URI, or PHP_SELF.
	 *
	 * @param   string   URI of the request
	 * @return  Request
	 */

	public static function instance( & $uri = TRUE)
	{
		static $instance;

		if ($instance === NULL)
		{
				if (isset($_SERVER['REQUEST_METHOD']))
				{
					// Use the server request method
					Request::$method = $_SERVER['REQUEST_METHOD'];
				}

    			if ( ! empty($_SERVER['HTTPS']) AND filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN))
				{
					// This request is secure
					Request::$protocol = 'https';
				}

				if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
				{
					// This request is an AJAX request
					Request::$is_ajax = TRUE;
				}

				if (isset($_SERVER['HTTP_REFERER']))
				{
					// There is a referrer for this request
					Request::$referrer = $_SERVER['HTTP_REFERER'];
				}

				if (isset($_SERVER['HTTP_USER_AGENT']))
				{
					// Set the client user agent
					Request::$user_agent = $_SERVER['HTTP_USER_AGENT'];
				}

				if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				{
					// Use the forwarded IP address, typically set when the
					// client is using a proxy server.
					Request::$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				}
				elseif (isset($_SERVER['HTTP_CLIENT_IP']))
				{
					// Use the forwarded IP address, typically set when the
					// client is using a proxy server.
					Request::$client_ip = $_SERVER['HTTP_CLIENT_IP'];
				}
				elseif (isset($_SERVER['REMOTE_ADDR']))
				{
					// The remote IP address
					Request::$client_ip = $_SERVER['REMOTE_ADDR'];
				}

				if (Request::$method !== 'GET' AND Request::$method !== 'POST')
				{
					// Methods besides GET and POST do not properly parse the form-encoded
					// query string into the $_POST array, so we overload it manually.
					parse_str(file_get_contents('php://input'), $_POST);
				}

				if ($uri === TRUE)
				{
					if ( ! empty($_SERVER['PATH_INFO']))
					{
						// PATH_INFO does not contain the docroot or index
						$uri = $_SERVER['PATH_INFO'];
					}
					else
					{
						// REQUEST_URI and PHP_SELF include the docroot and index

						if (isset($_SERVER['REQUEST_URI']))
						{
							// REQUEST_URI includes the query string, remove it
							$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
						}
						elseif (isset($_SERVER['PHP_SELF']))
						{
							$uri = $_SERVER['PHP_SELF'];
						}
						elseif (isset($_SERVER['REDIRECT_URL']))
						{
							$uri = $_SERVER['REDIRECT_URL'];
						}
						else
						{
							// If you ever see this error, please report an issue at and include a dump of $_SERVER
							// http://dev.kohanaphp.com/projects/kohana3/issues
							throw new SException('Unable to detect the URI using PATH_INFO, REQUEST_URI, or PHP_SELF');
						}
						// Get the path from the base URL, including the index file
						$base_url = parse_url('/', PHP_URL_PATH);

						if (strpos($uri, $base_url) === 0)
						{
							// Remove the base URL from the URI
							$uri = substr($uri, strlen($base_url));
						}

					}
				
			}

			// Reduce multiple slashes to a single slash
			$uri = preg_replace('#//+#', '/', $uri);

    		// Remove all dot-paths from the URI, they are not valid
			$uri = preg_replace('#\.[\s./]*/#', '', $uri);

			// Create the instance singleton
			$instance = new Request($uri);
			$instance->query($_GET)->post($_POST);

			// Add the Content-Type header
			$instance->headers['Content-Type'] = 'text/html; charset=UTF-8';
		}

		return $instance;
	}



	/**
	 * Creates a new request object for the given URI.
	 *
	 * @param   string  URI of the request
	 * @return  Request
	 */
	public static function factory($uri)
	{
		return new Request($uri);
	}


	public static function current()
	{
		return Request::$current;
	}
	
	
	
	/**

	 * Returns information about the client user agent.

	 *

	 * @param   string  value to return: browser, version, robot, mobile, platform

	 * @return  string  requested information

	 * @return  FALSE   no information found

	 */

	public static function user_agent($value)

	{

		static $info;



		if (isset($info[$value]))

		{

			// This value has already been found

			return $info[$value];

		}



		if ($value === 'browser' OR $value == 'version')

		{

			// Load browsers

			$browsers = Kohana::config('user_agents')->browser;



			foreach ($browsers as $search => $name)

			{

				if (stripos(Request::$user_agent, $search) !== FALSE)

				{

					// Set the browser name

					$info['browser'] = $name;



					if (preg_match('#'.preg_quote($search).'[^0-9.]*+([0-9.][0-9.a-z]*)#i', Request::$user_agent, $matches))

					{

						// Set the version number

						$info['version'] = $matches[1];

					}

					else

					{

						// No version number found

						$info['version'] = FALSE;

					}



					return $info[$value];

				}

			}

		}

		else

		{

			// Load the search group for this type

			$group = Kohana::config('user_agents')->$value;



			foreach ($group as $search => $name)

			{

				if (stripos(Request::$user_agent, $search) !== FALSE)

				{

					// Set the value name

					return $info[$value] = $name;

				}

			}

		}



		// The value requested could not be found

		return $info[$value] = FALSE;

	}



	/**

	 * Returns the accepted content types. If a specific type is defined,

	 * the quality of that type will be returned.

	 *

	 * @param   string  content MIME type

	 * @return  float   when checking a specific type

	 * @return  array

	 */

	public static function accept_type($type = NULL)

	{

		static $accepts;



		if ($accepts === NULL)

		{

			// Parse the HTTP_ACCEPT header

			$accepts = Request::_parse_accept($_SERVER['HTTP_ACCEPT'], array('*/*' => 1.0));

		}



		if (isset($type))

		{

			// Return the quality setting for this type

			return isset($accepts[$type]) ? $accepts[$type] : $accepts['*/*'];

		}



		return $accepts;

	}



	/**

	 * Returns the accepted languages. If a specific language is defined,

	 * the quality of that language will be returned. If the language is not

	 * accepted, FALSE will be returned.

	 *

	 * @param   string  language code

	 * @return  float   when checking a specific language

	 * @return  array

	 */

	public static function accept_lang($lang = NULL)

	{

		static $accepts;



		if ($accepts === NULL)

		{

			// Parse the HTTP_ACCEPT_LANGUAGE header

			$accepts = Request::_parse_accept($_SERVER['HTTP_ACCEPT_LANGUAGE']);

		}



		if (isset($lang))

		{

			// Return the quality setting for this lang

			return isset($accepts[$lang]) ? $accepts[$lang] : FALSE;

		}



		return $accepts;

	}



	/**

	 * Returns the accepted encodings. If a specific encoding is defined,

	 * the quality of that encoding will be returned. If the encoding is not

	 * accepted, FALSE will be returned.

	 *

	 * @param   string  encoding type

	 * @return  float   when checking a specific encoding

	 * @return  array

	 */

	public static function accept_encoding($type = NULL)

	{

		static $accepts;



		if ($accepts === NULL)

		{

			// Parse the HTTP_ACCEPT_LANGUAGE header

			$accepts = Request::_parse_accept($_SERVER['HTTP_ACCEPT_ENCODING']);

		}



		if (isset($type))

		{

			// Return the quality setting for this type

			return isset($accepts[$type]) ? $accepts[$type] : FALSE;

		}



		return $accepts;

	}



	/**

	 * Parses an accept header and returns an array (type => quality) of the

	 * accepted types, ordered by quality.

	 *

	 * @param   string   header to parse

	 * @param   array    default values

	 * @return  array

	 */

	protected static function _parse_accept( & $header, array $accepts = NULL)

	{

		if ( ! empty($header))

		{

			// Get all of the types

			$types = explode(',', $header);



			foreach ($types as $type)

			{

				// Split the type into parts

				$parts = explode(';', $type);



				// Make the type only the MIME

				$type = trim(array_shift($parts));



				// Default quality is 1.0

				$quality = 1.0;



				foreach ($parts as $part)

				{

					// Prevent undefined $value notice below

					if (strpos($part, '=') === FALSE)

						continue;



					// Separate the key and value

					list ($key, $value) = explode('=', trim($part));



					if ($key === 'q')

					{

						// There is a quality for this type

						$quality = (float) trim($value);

					}

				}



				// Add the accept type and quality

				$accepts[$type] = $quality;

			}

		}



		// Make sure that accepts is an array

		$accepts = (array) $accepts;



		// Order by quality

		arsort($accepts);



		return $accepts;

	}



	/**

	 * @var  object  route matched for this request

	 */

	public $route;



	/**

	 * @var  integer  HTTP response code: 200, 404, 500, etc

	 */

	public $status = 200;



	/**

	 * @var  string  response body

	 */

	public $response = '';



	/**

	 * @var  array  headers to send with the response body

	 */

	public $headers = array();



	/**

	 * @var  string  controller directory

	 */

	public $directory = '';



	/**

	 * @var  string  controller to be executed

	 */

	public $controller;



	/**

	 * @var  string  action to be executed in the controller

	 */

	public $action;





	/**

	 * @var  string  the URI of the request

	 */

	public $uri;



	// Parameters extracted from the route

	protected $_params;



	/**

	 * Creates a new request object for the given URI.

	 * Throws an exception when no route can be found for the URI.

	 *

	 * @throws  Kohana_Request_Exception

	 * @param   string  URI of the request

	 * @return  void

	 */

	public function __construct($uri)

	{

		// Remove trailing slashes from the URI

		$uri = trim($uri, '/');



		// Load routes

		$routes = Route::all();

		

		foreach ($routes as $name => $route)

		{



			if ($params = $route->matches($uri))

			{



				// Store the URI

				$this->uri = $uri;



				// Store the matching route

				$this->route = $route;



				if (isset($params['directory']))

				{

					// Controllers are in a sub-directory

					$this->directory = $params['directory'];

				}



				// Store the controller

				$this->controller = $params['controller'];



				if (isset($params['action']))

				{

					// Store the action

					$this->action = $params['action'];

				}

				else

				{

					// Use the default action

					$this->action = Route::$default_action;

				}



				// These are accessible as public vars and can be overloaded

				unset($params['controller'], $params['action'], $params['directory']);



				// Params cannot be changed once matched

				$this->_params = $params;





				return;

			}

		}



		// No matching route for this URI

		$this->status = 404;

header("Location: /");

// WYŁĄCZONY BŁĄD		throw new Exception('Unable to find a route to match the URI: '.$uri.'');

	}



	/**

	 * Returns the response as the string representation of a request.

	 *

	 * @return  string

	 */

	public function __toString()

	{

		return (string) $this->response;

	}



	/**

	 * Generates a relative URI for the current route.

	 *

	 * @param   array   additional route parameters

	 * @return  string

	 */

	public function uri(array $params = NULL)

	{

		if ( ! isset($params['directory']))

		{

			// Add the current directory

			$params['directory'] = $this->directory;

		}



		if ( ! isset($params['controller']))

		{

			// Add the current controller

			$params['controller'] = $this->controller;

		}



		if ( ! isset($params['action']))

		{

			// Add the current action

			$params['action'] = $this->action;

		}



		// Add the current parameters

		$params += $this->_params;



		return $this->route->uri($params);

	}



	/**

	 * Retrieves a value from the route parameters.

	 *

	 * @param   string   key of the value

	 * @param   mixed    default value if the key is not set

	 * @return  mixed

	 */

	public function param($key = NULL, $default = NULL)

	{

		if ($key === NULL)

		{

			// Return the full array

			return $this->_params;

		}



		return isset($this->_params[$key]) ? $this->_params[$key] : $default;

	}


public function route(Route $route = NULL)
	{
		if ($route === NULL)
		{
			// Act as a getter
			return $this->_route;
		}

		// Act as a setter
		$this->_route = $route;

		return $this;
	}


	/**

	 * Sends the response status and all set headers.

	 *

	 * @return  $this

	 */

	public function send_headers()
	{
		if ( ! headers_sent())
		{
			if (isset($_SERVER['SERVER_PROTOCOL']))
			{
				$protocol = $_SERVER['SERVER_PROTOCOL'];
			}
			else
			{
				$protocol = 'HTTP/1.1';
			}
			header($protocol.' '.$this->status.' '.Request::$messages[$this->status]);
			foreach ($this->headers as $name => $value)
			{
				if (is_string($name))
				{
					$value = "{$name}: {$value}";
				}
				header($value, TRUE);
			}
		}
		return $this;
	}



	/**

	 * Redirects as the request response.

	 *

	 * @param   string   redirect location

	 * @param   integer  status code

	 * @return  void

	 */

	public function redirect($url, $code = 302)

	{

		if (strpos($url, '://') === FALSE)
		{
			// Make the URI into a URL
			$url = URL::site($url, TRUE);
		}

		// Set the response status

		$this->status = $code;



		// Set the location header

		$this->headers['Location'] = $url;



		// Send headers

		$this->send_headers();



		// Stop execution

		exit;

	}



	

	

	public function execute()
	{
		
		//require_once(APPPATH."class/controller/".$this->directory.'/'.$this->controller.'.php');
		
		// Create the class prefix
		$prefix = 'Controller_';

		if ($this->directory)
		{
			// Add the directory name to the class prefix
			$prefix .= str_replace(array('\\', '/'), '_', trim($this->directory, '/')).'_';
		}

		// Store the currently active request
		$previous = Request::$current;

		// Change the current request to this request
		Request::$current = $this;

		try
		{
			// Load the controller using reflection
			$class = new ReflectionClass($prefix.$this->controller);

			if ($class->isAbstract())
			{
				throw new Exception('Cannot create instances of abstract '.$prefix.$this->controller);
			}

			// Create a new instance of the controller
			$controller = $class->newInstance($this);

			// Execute the "before action" method
			$class->getMethod('before')->invoke($controller);

			// Determine the action to use
			$action = empty($this->action) ? Route::$default_action : $this->action;

			// Execute the main action with the parameters
			$class->getMethod('action_'.$action)->invokeArgs($controller, $this->_params);

			// Execute the "after action" method
			$class->getMethod('after')->invoke($controller);
		}
		catch (Exception $e)
		{
			// Restore the previous request
			Request::$current = $previous;

			if ($e instanceof ReflectionException)
			{
				// Reflection will throw exceptions for missing classes or actions
				$this->status = 404;
			}
			else
			{
				// All other exceptions are PHP/server errors
				$this->status = 500;
			}

			// Re-throw the exception
			throw $e;
		}

		// Restore the previous request
		Request::$current = $previous;

		return $this;
	}





	/**

	 * Generate ETag

	 * Generates an ETag from the response ready to be returned

	 *

	 * @throws Kohana_Request_Exception

	 * @return String Generated ETag

	 */

	public function generate_etag()

	{

	    if ($this->response === NULL)

		{

			throw new Kohana_Request_Exception('No response yet associated with request - cannot auto generate resource ETag');

		}



		// Generate a unique hash for the response

		return '"'.sha1($this->response).'"';

	}





	/**

	 * Check Cache

	 * Checks the browser cache to see the response needs to be returned

	 *

	 * @param String Resource ETag

	 * @throws Kohana_Request_Exception

	 * @chainable

	 */

	public function check_cache($etag = null)

	{

		if (empty($etag))

		{

			$etag = $this->generate_etag();

		}



		// Set the ETag header

		$this->headers['ETag'] = $etag;



		// Add the Cache-Control header if it is not already set

		// This allows etags to be used with Max-Age, etc

		$this->headers += array(

			'Cache-Control' => 'must-revalidate',

		);



		if (isset($_SERVER['HTTP_IF_NONE_MATCH']) AND $_SERVER['HTTP_IF_NONE_MATCH'] === $etag)

		{

			// No need to send data again

			$this->status = 304;

			$this->send_headers();



			// Stop execution

			exit;

		}



		return $this;

	}
	
	    public function method($method = NULL)
	{
		if ($method === NULL)
		{
			// Act as a getter
			return $this->_method;
		}

		// Act as a setter
		$this->_method = strtoupper($method);

		return $this;
	}
	
	public function protocol($protocol = NULL)
	{
		if ($protocol === NULL)
		{
			if ($this->_protocol)
				return $this->_protocol;
			else
				return $this->_protocol = HTTP::$protocol;
		}

		// Act as a setter
		$this->_protocol = strtoupper($protocol);
		return $this;
	}
	
	public function headers($key = NULL, $value = NULL)
	{
		if ($key instanceof HTTP_Header)
		{
			// Act a setter, replace all headers
			$this->_header = $key;

			return $this;
		}

		if (is_array($key))
		{
			// Act as a setter, replace all headers
			$this->_header->exchangeArray($key);

			return $this;
		}

		if ($this->_header->count() === 0 AND $this->is_initial())
		{
			// Lazy load the request headers
			$this->_header = HTTP::request_headers();
		}

		if ($key === NULL)
		{
			// Act as a getter, return all headers
			return $this->_header;
		}
		elseif ($value === NULL)
		{
			// Act as a getter, single header
			return ($this->_header->offsetExists($key)) ? $this->_header->offsetGet($key) : NULL;
		}

		// Act as a setter for a single header
		$this->_header[$key] = $value;

		return $this;
	}
	
	public function body($content = NULL)
	{
		if ($content === NULL)
		{
			// Act as a getter
			return $this->_body;
		}

		// Act as a setter
		$this->_body = $content;

		return $this;
	}
		
		
		public function render()
	{
		if ( ! $post = $this->post())
		{
			$body = $this->body();
		}
		else
		{
			$this->headers('content-type', 'application/x-www-form-urlencoded');
			$body = http_build_query($post, NULL, '&');
		}

		// Set the content length
		$this->headers('content-length', (string) $this->content_length());

		// Prepare cookies
		if ($this->_cookies)
		{
			$cookie_string = array();

			// Parse each
			foreach ($this->_cookies as $key => $value)
			{
				$cookie_string[] = $key.'='.$value;
			}

			// Create the cookie string
			$this->_header['cookie'] = implode('; ', $cookie_string);
		}

		$output = $this->method().' '.$this->uri().' '.$this->protocol()."\r\n";
		$output .= (string) $this->_header;
		$output .= $body;

		return $output;
	}

	public function query($key = NULL, $value = NULL)
	{
		if (is_array($key))
		{
			// Act as a setter, replace all query strings
			$this->_get = $key;

			return $this;
		}

		if ($key === NULL)
		{
			// Act as a getter, all query strings
			return $this->_get;
		}
		elseif ($value === NULL)
		{
			// Act as a getter, single query string
			return Arr::path($this->_get, $key);
		}

		// Act as a setter, single query string
		$this->_get[$key] = $value;

		return $this;
	}

	public function post($key = NULL, $value = NULL)
	{
		if (is_array($key))
		{
			// Act as a setter, replace all fields
			$this->_post = $key;

			return $this;
		}

		if ($key === NULL)
		{
			// Act as a getter, all fields
			return $this->_post;
		}
		elseif ($value === NULL)
		{
			// Act as a getter, single field
			return Arr::path($this->_post, $key);
		}

		// Act as a setter, single field
		$this->_post[$key] = $value;

		return $this;
	}
		
		
} // End Request

