<?php 
abstract class Request_Client {

	/**
	 * @var    Cache  Caching library for request caching
	 */
	protected $_cache;

	/**
	 * @var  bool  Should redirects be followed?
	 */
	protected $_follow = FALSE;

	/**
	 * @var  array  Headers to preserve when following a redirect
	 */
	protected $_follow_headers = array('Authorization');

	/**
	 * @var  bool  Follow 302 redirect with original request method?
	 */
	protected $_strict_redirect = TRUE;

	/**
	 * @var array  Callbacks to use when response contains given headers
	 */
	protected $_header_callbacks = array(
		'Location'  => 'Request_Client::on_header_location'
	);

	/**
	 * @var int  Maximum number of requests that header callbacks can trigger before the request is aborted
	 */
	protected $_max_callback_depth = 5;

	/**
	 * @var int  Tracks the callback depth of the currently executing request
	 */
	protected $_callback_depth = 1;

	/**
	 * @var array  Arbitrary parameters that are shared with header callbacks through their Request_Client object
	 */
	protected $_callback_params = array();

	/**
	 * Creates a new `Request_Client` object,
	 * allows for dependency injection.
	 *
	 * @param   array    $params Params
	 */
	public function __construct(array $params = array())
	{
		foreach ($params as $key => $value)
		{
			if (method_exists($this, $key))
			{
				$this->$key($value);
			}
		}
	}

	public function execute(Request $request)
	{
		// Prevent too much recursion of header callback requests
		if ($this->callback_depth() > $this->max_callback_depth())
			throw new Request_Client_Recursion_Exception(
					"Could not execute request to :uri - too many recursions after :depth requests",
					array(
						':uri' => $request->uri(),
						':depth' => $this->callback_depth() - 1,
					));

		// Execute the request
		$orig_response = $response = Response::factory();

		if (($cache = $this->cache()) instanceof HTTP_Cache)
			return $cache->execute($this, $request, $response);

		$response = $this->execute_request($request, $response);

		// Execute response callbacks
		foreach ($this->header_callbacks() as $header => $callback)
		{
			if ($response->headers($header))
			{
				$cb_result = call_user_func($callback, $request, $response, $this);

				if ($cb_result instanceof Request)
				{
					// If the callback returns a request, automatically assign client params
					$this->assign_client_properties($cb_result->client());
					$cb_result->client()->callback_depth($this->callback_depth() + 1);

					// Execute the request
					$response = $cb_result->execute();
				}
				elseif ($cb_result instanceof Response)
				{
					// Assign the returned response
					$response = $cb_result;
				}

				// If the callback has created a new response, do not process any further
				if ($response !== $orig_response)
					break;
			}
		}

		return $response;
	}

	abstract public function execute_request(Request $request, Response $response);

	public function cache(HTTP_Cache $cache = NULL)
	{
		if ($cache === NULL)
			return $this->_cache;

		$this->_cache = $cache;
		return $this;
	}

	/**
	 * Getter and setter for the follow redirects
	 * setting.
	 *
	 * @param   bool  $follow  Boolean indicating if redirects should be followed
	 * @return  bool
	 * @return  Request_Client
	 */
	public function follow($follow = NULL)
	{
		if ($follow === NULL)
			return $this->_follow;

		$this->_follow = $follow;

		return $this;
	}

	public function follow_headers($follow_headers = NULL)
	{
		if ($follow_headers === NULL)
			return $this->_follow_headers;

		$this->_follow_headers = $follow_headers;

		return $this;
	}

	public function strict_redirect($strict_redirect = NULL)
	{
		if ($strict_redirect === NULL)
			return $this->_strict_redirect;

		$this->_strict_redirect = $strict_redirect;

		return $this;
	}

	public function header_callbacks($header_callbacks = NULL)
	{
		if ($header_callbacks === NULL)
			return $this->_header_callbacks;

		$this->_header_callbacks = $header_callbacks;

		return $this;
	}

	public function max_callback_depth($depth = NULL)
	{
		if ($depth === NULL)
			return $this->_max_callback_depth;

		$this->_max_callback_depth = $depth;

		return $this;
	}

	public function callback_depth($depth = NULL)
	{
		if ($depth === NULL)
			return $this->_callback_depth;

		$this->_callback_depth = $depth;

		return $this;
	}

	public function callback_params($param = NULL, $value = NULL)
	{
		// Getter for full array
		if ($param === NULL)
			return $this->_callback_params;

		// Setter for full array
		if (is_array($param))
		{
			$this->_callback_params = $param;
			return $this;
		}
		// Getter for single value
		elseif ($value === NULL)
		{
			return Arr::get($this->_callback_params, $param);
		}
		// Setter for single value
		else
		{
			$this->_callback_params[$param] = $value;
			return $this;
		}

	}

	public function assign_client_properties(Request_Client $client)
	{
		$client->cache($this->cache());
		$client->follow($this->follow());
		$client->follow_headers($this->follow_headers());
		$client->header_callbacks($this->header_callbacks());
		$client->max_callback_depth($this->max_callback_depth());
		$client->callback_params($this->callback_params());
	}

	public static function on_header_location(Request $request, Response $response, Request_Client $client)
	{
		// Do we need to follow a Location header ?
		if ($client->follow() AND in_array($response->status(), array(201, 301, 302, 303, 307)))
		{
			// Figure out which method to use for the follow request
			switch ($response->status())
			{
				default:
				case 301:
				case 307:
					$follow_method = $request->method();
					break;
				case 201:
				case 303:
					$follow_method = Request::GET;
					break;
				case 302:
					// Cater for sites with broken HTTP redirect implementations
					if ($client->strict_redirect())
					{
						$follow_method = $request->method();
					}
					else
					{
						$follow_method = Request::GET;
					}
					break;
			}

			// Prepare the additional request
			$follow_request = Request::factory($response->headers('Location'))
			                         ->method($follow_method)
			                         ->headers(Arr::extract($request->headers(), $client->follow_headers()));

			if ($follow_method !== Request::GET)
			{
				$follow_request->body($request->body());
			}

			return $follow_request;
		}

		return NULL;
	}

}