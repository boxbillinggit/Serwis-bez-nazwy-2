<?php 

class Request_Client_Internal extends Request_Client {

	protected $_previous_environment;

	public function execute_request(Request $request, Response $response)
	{
		// Create the class prefix
		$prefix = 'Controller_';

		// Directory
		$directory = $request->directory();

		// Controller
		$controller = $request->controller();

		if ($directory)
		{
			// Add the directory name to the class prefix
			$prefix .= str_replace(array('\\', '/'), '_', trim($directory, '/')).'_';
		}

		// Store the currently active request
		$previous = Request::$current;

		// Change the current request to this request
		Request::$current = $request;

		// Is this the initial request
		$initial_request = ($request === Request::$initial);

		try
		{
			if ( ! class_exists($prefix.$controller))
			{
				throw HTTP_Exception::factory(404,
					'The requested URL :uri was not found on this server.',
					array(':uri' => $request->uri())
				)->request($request);
			}

			// Load the controller using reflection
			$class = new ReflectionClass($prefix.$controller);

			if ($class->isAbstract())
			{
				throw new Exception(
					'Cannot create instances of abstract :controller',
					array(':controller' => $prefix.$controller)
				);
			}

			// Create a new instance of the controller
			$controller = $class->newInstance($request, $response);

			// Run the controller's execute() method
			$response = $class->getMethod('execute')->invoke($controller);

			if ( ! $response instanceof Response)
			{
				// Controller failed to return a Response.
				throw new Exception('Controller failed to return a Response');
			}
		}
		catch (HTTP_Exception $e)
		{
			// Get the response via the Exception
			$response = $e->get_response();
		}
		catch (Exception $e)
		{
			// Generate an appropriate Response object
			$response = Exception::_handler($e);
		}

		// Restore the previous request
		Request::$current = $previous;

		// Return the response
		return $response;
	}
}
