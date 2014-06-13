<?php defined('SYSPATH') OR die('No direct script access.');

class Webcms_Gzip {
	
	public function init() {
		
		$gzip = DB::query(Database::SELECT, 'SELECT * FROM settings WHERE name="gzip"')->as_object(TRUE)->execute()->current();
		
		if($gzip->value == 'true') {
			Gzip::update();
		if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
		}
	}
	
	public function update() {

		$searchthis = "<ifModule mod_gzip.c>";
		$matches = array();
		
		$handle = @fopen(".htaccess", "r");
		if ($handle)
		{
			while (!feof($handle))
			{
				$buffer = fgets($handle);
				if(strpos($buffer, $searchthis) !== FALSE)
					$matches[] = $buffer;
			}
			fclose($handle);
		}

		// If the text was not found, show a message
		if(!$matches)
		{
				//header('Content-Type: text/plain');
				$file = '.htaccess';
				$current = file_get_contents($file);
				$current .= "\n
				<ifModule mod_gzip.c>
				mod_gzip_on Yes
				mod_gzip_dechunk Yes
				mod_gzip_item_include file .(html?|txt|css|js|php|pl)$
				mod_gzip_item_include handler ^cgi-script$
				mod_gzip_item_include mime ^text/.*
				mod_gzip_item_include mime ^application/x-javascript.*
				mod_gzip_item_exclude mime ^image/.*
				mod_gzip_item_exclude rspheader ^Content-Encoding:.*gzip.*
				</ifModule>
				
				AddOutputFilterByType DEFLATE text/plain
				AddOutputFilterByType DEFLATE text/html
				AddOutputFilterByType DEFLATE text/xml
				AddOutputFilterByType DEFLATE text/css
				AddOutputFilterByType DEFLATE application/xml
				AddOutputFilterByType DEFLATE application/xhtml+xml
				AddOutputFilterByType DEFLATE application/rss+xml
				AddOutputFilterByType DEFLATE application/javascript
				AddOutputFilterByType DEFLATE application/x-javascript";
				file_put_contents($file, $current);	
				Request::instance()->redirect('/admin/settings');	
		}

	}
	
	public function remove() {
		
		
		$remove = "
				RewriteEngine On

				RewriteCond %{HTTP_HOST} !^www\. [NC]
				RewriteRule ^(.*)$ http://www.%{HTTP_HOST}/$1 [L,R=301]
				
				RewriteCond %{REQUEST_FILENAME} -s [OR]
				RewriteCond %{REQUEST_FILENAME} -l [OR]
				RewriteCond %{REQUEST_FILENAME} -d
				RewriteRule ^.*$ - [NC,L]
				RewriteRule ^.*$ index.php [NC,L]";
	
	$searchthis = "<ifModule mod_gzip.c>";
		$matches = array();
		
		$handle = @fopen(".htaccess", "r");
		if ($handle)
		{
			while (!feof($handle))
			{
				$buffer = fgets($handle);
				if(strpos($buffer, $searchthis) !== FALSE)
					$matches[] = $buffer;
			}
			fclose($handle);
		}

	if(empty($matches)) {} else 
		{
	
			if (file_exists(".htaccess")) {
				unlink(".htaccess");
				file_put_contents(".htaccess", $remove);
				Request::instance()->redirect('/admin/settings');	
			} else {
				echo "The file $filename does not exist";
			}
		
		}
		
	}
	
}

?>