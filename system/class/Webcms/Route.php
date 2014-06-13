<?php



class Webcms_Route {

static protected $params = array();
static protected $uri = array();
static protected $controller_dir = CONTROLLER;
//static protected $default_action = 'index';
public static $content = '';
protected static $_routes = array();

	const REGEX_KEY     = '<([a-zA-Z0-9_]++)>';
	const REGEX_SEGMENT = '[^/.,;?\n]++';
	const REGEX_ESCAPE  = '[.\\+*?[^\\]${}=!|]';

	/**
	 * @var  string  default action for all routes
	 */

	public static $default_action = 'index';

	public static function set($name, $uri, array $regex = NULL)
	{
		return Route::$_routes[$name] = new Route($uri, $regex);
	}

	

	public static function all()
	{
		return Route::$_routes;
	}

	public static function name(Route $route)
	{
		return array_search($route, Route::$_routes);
	}

	public static function get($name)
	{
		if ( ! isset(Route::$_routes[$name]))
		{
			throw new Exception('The requested route does not exist: :route',
				array(':route' => $name));
		}
		return Route::$_routes[$name];
	}

	// Route URI string
	protected $_uri = '';

	// Regular expressions for route keys
	protected $_regex = array();

	// Default values for route keys
	protected $_defaults = array('action' => 'index');

	// Compiled regex cache
	protected $_route_regex;

	/**
	 * Creates a new route. Sets the URI and regular expressions for keys.
	 *
	 * @param   string   route URI pattern
	 * @param   array    key patterns
	 * @return  void
	 */

	public function __construct($uri = NULL, array $regex = NULL)
	{
		if ($uri === NULL)
		{
			// Assume the route is from cache
			return;
		}

		if ( ! empty($regex))
		{
			$this->_regex = $regex;
		}

		// Store the URI that this route will match
		$this->_uri = $uri;

		// Store the compiled regex locally
		$this->_route_regex = $this->_compile();
	}

	/**
	 * Provides default values for keys when they are not present. The default
	 * action will always be "index" unless it is overloaded here.
	 *
	 *     $route->defaults(array('controller' => 'welcome', 'action' => 'index'));
	 *
	 * @param   array  key values
	 * @return  Route
	 */
	public function defaults(array $defaults = NULL)
	{
		$this->_defaults = $defaults;

		return $this;
	}

	/**
	 * Tests if the route matches a given URI. A successful match will return
	 * all of the routed parameters as an array. A failed match will return
	 * boolean FALSE.
	 *
	 *     // This route will only match if the <controller>, <action>, and <id> exist
	 *     $params = Route::factory('<controller>/<action>/<id>', array('id' => '\d+'))
	 *         ->matches('users/edit/10');
	 *     // The parameters are now: controller = users, action = edit, id = 10
	 *
	 * This method should almost always be used within an if/else block:
	 *
	 *     if ($params = $route->matches($uri))
	 *     {
	 *         // Parse the parameters
	 *     }
	 *
	 * @param   string  URI to match
	 * @return  array   on success
	 * @return  FALSE   on failure
	 */
	public function matches($uri)
	{
		if ( ! preg_match($this->_route_regex, $uri, $matches))
			return FALSE;

		$params = array();
		foreach ($matches as $key => $value)
		{
			if (is_int($key))
			{
				// Skip all unnamed keys
				continue;
			}

			// Set the value for all matched keys
			$params[$key] = $value;
		}

		foreach ($this->_defaults as $key => $value)
		{
			if ( ! isset($params[$key]) OR $params[$key] === '')
			{
				// Set default values for any key that was not matched
				$params[$key] = $value;
			}
		}

		return $params;
	}

	/**
	 * Generates a URI for the current route based on the parameters given.
	 *
	 * @param   array   URI parameters
	 * @return  string
	 */
	public function uri(array $params = NULL)
	{
		if ($params === NULL)
		{
			// Use the default parameters
			$params = $this->_defaults;
		}
		else
		{
			// Add the default parameters
			$params += $this->_defaults;
		}

		// Start with the routed URI
		$uri = $this->_uri;


		if (strpos($uri, '<') === FALSE AND strpos($uri, '(') === FALSE)

		{

			// This is a static route, no need to replace anything

			return $uri;

		}



		while (preg_match('#\([^()]++\)#', $uri, $match))

		{

			// Search for the matched value

			$search = $match[0];



			// Remove the parenthesis from the match as the replace

			$replace = substr($match[0], 1, -1);



			while(preg_match('#'.Route::REGEX_KEY.'#', $replace, $match))

			{

				list($key, $param) = $match;



				if (isset($params[$param]))

				{

					// Replace the key with the parameter value

					$replace = str_replace($key, $params[$param], $replace);

				}

				else

				{

					// This group has missing parameters

					$replace = '';

					break;

				}

			}



			// Replace the group in the URI

			$uri = str_replace($search, $replace, $uri);

		}



		while(preg_match('#'.Route::REGEX_KEY.'#', $uri, $match))

		{

			list($key, $param) = $match;



			if ( ! isset($params[$param]))

			{

				// Ungrouped parameters are required

				throw new Exception('Required route parameter not passed: :param',

					array(':param' => $param));

			}



			$uri = str_replace($key, $params[$param], $uri);

		}



		// Trim all extra slashes from the URI

		$uri = preg_replace('#//+#', '/', rtrim($uri, '/'));



		return $uri;

	}



	/**

	 * Returns the compiled regular expression for the route. This translates

	 * keys and optional groups to a proper PCRE regular expression.

	 *

	 * @access  protected

	 * @return  string

	 */

	protected function _compile()

	{

		// The URI should be considered literal except for keys and optional parts

		// Escape everything preg_quote would escape except for : ( ) < >

		$regex = preg_replace('#'.Route::REGEX_ESCAPE.'#', '\\\\$0', $this->_uri);



		if (strpos($regex, '(') !== FALSE)

		{

			// Make optional parts of the URI non-capturing and optional

			$regex = str_replace(array('(', ')'), array('(?:', ')?'), $regex);

		}



		// Insert default regex for keys

		$regex = str_replace(array('<', '>'), array('(?P<', '>'.Route::REGEX_SEGMENT.')'), $regex);



		if ( ! empty($this->_regex))

		{

			$search = $replace = array();

			foreach ($this->_regex as $key => $value)

			{

				$search[]  = "<$key>".Route::REGEX_SEGMENT;

				$replace[] = "<$key>$value";

			}



			// Replace the default regex with the user-specified regex

			$regex = str_replace($search, $replace, $regex);

		}



		return '#^'.$regex.'$#uD';

	}

	



/*

public static function set($uri,$argumenty,$ustwienia) {

require_once(Route::$controller_dir.$ustwienia['directory'].'/'.$ustwienia['controller'].'.php');

$check = Route::set_link();



if(empty($check)) {

	call_user_func(''.$ustwienia['controller'].'::action_'.$ustwienia['action']);

} else {

	call_user_func(''.$ustwienia['controller'].'::action_'.$check);

	$cc = call_user_func(''.$ustwienia['controller'].'::action_'.$check);

	if(empty($cc)) { 

	ob_start();

	ob_flush();

	header("Location: /error/404");

	ob_end_flush(); }

	

}

  

  



//throw new Exception( header("Location: /error/404"));

//Home::action_index();

//echo $content;

}*/



public static function set_link() {

$action = explode("/",$_SERVER["REQUEST_URI"]);

return $action[1];

}



}







?>