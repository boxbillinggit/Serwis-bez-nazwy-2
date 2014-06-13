<?php defined('SYSPATH') OR die('No direct access allowed.');

class Webcms_URL {

	public static function base($index = FALSE, $protocol = FALSE)
	{
		if ($protocol === TRUE)
		{
			// Use the current protocol
			$protocol = Request::$protocol;
		}

		// Start with the configured base URL
		$base_url = Webcms::$base_url;

		if ($index === TRUE AND ! empty(Webcms::$index_file))
		{
			// Add the index file to the URL
			$base_url .= Webcms::$index_file.'/';
		}

		if (is_string($protocol))
		{
			if (parse_url($base_url, PHP_URL_HOST))
			{
				// Remove everything but the path from the URL
				$base_url = parse_url($base_url, PHP_URL_PATH);
			}

			// Add the protocol and domain to the base URL
			$base_url = $protocol.'://'.$_SERVER['HTTP_HOST'].$base_url;
		}

		return $base_url;
	}

	public static function site($uri = '', $protocol = FALSE)
	{
		// Get the path from the URI
		$path = trim(parse_url($uri, PHP_URL_PATH), '/');

		if ($query = parse_url($uri, PHP_URL_QUERY))
		{
			// ?query=string
			$query = '?'.$query;
		}

		if ($fragment = parse_url($uri, PHP_URL_FRAGMENT))
		{
			// #fragment
			$fragment =  '#'.$fragment;
		}

		// Concat the URL
		return URL::base(TRUE, $protocol).$path.$query.$fragment;
	}

	public static function query(array $params = NULL)
	{
		if ($params === NULL)
		{
			// Use only the current parameters
			$params = $_GET;
		}
		else
		{
			// Merge the current and new parameters
			$params = array_merge($_GET, $params);
		}

		if (empty($params))
		{
			// No query parameters
			return '';
		}

		return '?'.http_build_query($params, '', '&');
	}

	public static function title($title, $separator = '-', $ascii_only = FALSE)
	{
		if ($ascii_only === TRUE)
		{
			// Transliterate non-ASCII characters
			$title = UTF8::transliterate_to_ascii($title);

			// Remove all characters that are not the separator, a-z, 0-9, or whitespace
			$title = preg_replace('![^'.preg_quote($separator).'a-z0-9\s]+!', '', strtolower($title));
		}
		else
		{
			// Remove all characters that are not the separator, letters, numbers, or whitespace
			$title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', UTF8::strtolower($title));
		}

		// Replace all separator characters and whitespace by a single separator
		$title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

		// Trim separators from the beginning and end
		return trim($title, $separator);
	}

	final private function __construct()
	{
		// This is a static class
	}

}