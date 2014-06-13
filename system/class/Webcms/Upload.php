<?php

class Webcms_Upload {
	
	public $directory;
	
	public $file;
	
	public $width;
	
	public $height;
	
	public $destination_file;
	
	public static $crop = 1;
	
	public static $normal = 'normal';
	
	public static $small = 'thumbs';
	
	public static function save($files, $directory)
	{		
				$tmp_name = $files["photo"]["tmp_name"];
				$name = $files["photo"]["name"];
				$zmien = explode(".", $name);
				$nowa_nazwa = md5(uniqid()).'.'.$zmien[1];				
				
				$nor = Upload::$normal;
				$thumb = Upload::$small;
				move_uploaded_file($tmp_name, "$directory/$nor/$nowa_nazwa");
				
				// Upload::resize("$directory/$nor/$nowa_nazwa",160,190,NULL,200,"$directory/$thumb/$nowa_nazwa");							
		        Upload::create_thumb_image("$directory/$nor/$nowa_nazwa", "$directory/$thumb/$nowa_nazwa", 170, 130);
				
				return $nowa_nazwa;
	}
	
	public static function resize($file, $width, $height, $crop = NULL, $square_size = 200, $thumb_file) 
	{
		/*
		if (Webcms_Upload::$crop == $crop) 
		{
			echo 'OK MOZNA ZROBIĆ CROP';
		}
		*/		
            if (isset($destination_file) and $destination_file != NULL)
            {
                if (!is_writable($destination_file))
                {                    
                }
            }

            // get width and height of original image
            $imagedata       = getimagesize($file);
            $original_width  = $imagedata[0];
            $original_height = $imagedata[1];
			
            if ($original_width > $original_height)
            {
                $new_height = $height;
                $new_width  = $width * ($original_width / $original_height);
            }
            if ($original_height > $original_width)
            {
                $new_width  = $square_size;
                $new_height = $new_width * ($original_height / $original_width);
            }
            if ($original_height == $original_width)
            {
                $new_width  = $square_size;
                $new_height = $square_size;
            }

            $new_width  = round($new_width);
            $new_height = round($new_height);

            if (substr_count(strtolower($file), ".jpg") or substr_count(strtolower($file), ".jpeg"))
            {
                $original_image = imagecreatefromjpeg($file);
            }
            if (substr_count(strtolower($file), ".gif"))
            {
                $original_image = imagecreatefromgif($file);
            }
            if (substr_count(strtolower($file), ".png"))
            {
                $original_image = imagecreatefrompng($file);
            }

            $smaller_image = imagecreatetruecolor($new_width, $new_height);
            $square_image  = imagecreatetruecolor($square_size, $square_size);

            imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

            if ($new_width > $new_height)
            {
                $difference      = $new_width - $new_height;
                $half_difference = round($difference / 2);
                imagecopyresampled($square_image, $smaller_image, 0 - $half_difference + 1, 0, 0, 0, $square_size + $difference, $square_size, $new_width, $new_height);
            }
            if ($new_height > $new_width)
            {
                $difference      = $new_height - $new_width;
                $half_difference = round($difference / 2);
                imagecopyresampled($square_image, $smaller_image, 0, 0 - $half_difference + 1, 0, 0, $square_size, $square_size + $difference, $new_width, $new_height);
            }
            if ($new_height == $new_width)
            {
                imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
            }

            if (!$thumb_file)
            {
                imagepng($square_image, NULL, 9);
            }

            if (substr_count(strtolower($thumb_file), ".jpg"))
            {
                imagejpeg($square_image, $thumb_file, 100);
            }
            if (substr_count(strtolower($thumb_file), ".gif"))
            {
                imagegif($square_image, $thumb_file);
            }
            if (substr_count(strtolower($thumb_file), ".png"))
            {
                imagepng($square_image, $thumb_file, 9);
            }

            imagedestroy($original_image);
            imagedestroy($smaller_image);
            imagedestroy($square_image);		
	}
	
	
	
        public static function create_thumb_image($original_file, $destination_file = NULL, $thumb_width, $thumb_height)
        {

            if (isset($destination_file) and $destination_file != NULL)
            {
                if (!is_writable($destination_file))
                {                    
                }
            }

            // get width and height of original image
            $imagedata       = getimagesize($original_file);
            $original_width  = $imagedata[0];
            $original_height = $imagedata[1];

            $ratio_height = ($original_height / $thumb_height);
            $ratio_width = ($original_width / $thumb_width);

            if ($ratio_width > $ratio_height)
            {
                $new_height = $thumb_height;
                $new_width = $new_height * ($original_width / $original_height);
            }
            
            if ($ratio_width < $ratio_height)
            {
                $new_width = $thumb_width;
                $new_height = $new_width * ($original_height / $original_width);
            }            
            
            if ($ratio_width == $ratio_height)
            {
                $new_width  = $thumb_width;
                $new_height = $thumb_height;
            }

            $new_width  = round($new_width);
            $new_height = round($new_height);

            if (substr_count(strtolower($original_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg"))
            {
                $original_image = imagecreatefromjpeg($original_file);
            }
            if (substr_count(strtolower($original_file), ".gif"))
            {
                $original_image = imagecreatefromgif($original_file);
            }
            if (substr_count(strtolower($original_file), ".png"))
            {
                $original_image = imagecreatefrompng($original_file);
            }

            $smaller_image = imagecreatetruecolor($new_width, $new_height);
            $square_image  = imagecreatetruecolor($thumb_width, $thumb_height);

            imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

            if ($ratio_width > $ratio_height)
            {
                $difference      = $new_width - $thumb_width;
                $half_difference = round($difference / 2);
                imagecopyresampled($square_image, $smaller_image, 0 - $half_difference + 1, 0, 0, 0, $thumb_width + $difference, $thumb_height, $new_width, $new_height);
            }
            if ($ratio_width < $ratio_height)
            {
                $difference      = $new_height - $thumb_height;
                $half_difference = round($difference / 2);
                imagecopyresampled($square_image, $smaller_image, 0, 0 - $half_difference + 1, 0, 0, $thumb_width, $thumb_height + $difference, $new_width, $new_height);
            }
            if ($ratio_width == $ratio_height)
            {
                imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $new_width, $new_height);
            }

            if (!$destination_file)
            {
                imagepng($square_image, NULL, 9);
            }

            if (substr_count(strtolower($destination_file), ".jpg"))
            {
                imagejpeg($square_image, $destination_file, 100);
            }
            if (substr_count(strtolower($destination_file), ".gif"))
            {
                imagegif($square_image, $destination_file);
            }
            if (substr_count(strtolower($destination_file), ".png"))
            {
                imagepng($square_image, $destination_file, 9);
            }

            imagedestroy($original_image);
            imagedestroy($smaller_image);
            imagedestroy($square_image);
        }   	
	
	
	
	
	
	
	
	
	
	public static function upload_news($directory,$ai) {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	// Settings
	//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
	//$targetDir = 'uploads';
	$targetDir = $directory.'/'.Upload::$normal.'';
	
	$cleanupTargetDir = true; // Remove old files
	$maxFileAge = 5 * 3600; // Temp file age in seconds
	
	// 5 minutes execution time
	@set_time_limit(5 * 60);
	
	// Uncomment this one to fake upload time
	// usleep(5000);
	
	// Get parameters
	$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
	$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
	
	// Clean the fileName for security reasons
	$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
	
	// Make sure the fileName is unique but only if chunking is disabled
	if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
		$ext = strrpos($fileName, '.');
		$fileName_a = substr($fileName, 0, $ext);
		$fileName_b = substr($fileName, $ext);
	
		$count = 1;
		while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
			$count++;
	
		$fileName = $fileName_a . '_' . $count . $fileName_b;
	}
	
	$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
	
	// Create target dir
	if (!file_exists($targetDir))
		@mkdir($targetDir);
	
	// Remove old temp files	
	if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
		while (($file = readdir($dir)) !== false) {
			$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
	
			// Remove temp file if it is older than the max age and is not the current file
			if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
				@unlink($tmpfilePath);
			}
		}
	
		closedir($dir);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		
	
	// Look for the content type header
	if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
	
	if (isset($_SERVER["CONTENT_TYPE"]))
		$contentType = $_SERVER["CONTENT_TYPE"];
	
	// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
	if (strpos($contentType, "multipart") !== false) {
		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen($_FILES['file']['tmp_name'], "rb");
	
				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				fclose($in);
				fclose($out);
				@unlink($_FILES['file']['tmp_name']);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	} else {
		// Open temp file
		$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen("php://input", "rb");
	
			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	
			fclose($in);
			fclose($out);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	}
	
	// Check if file has been uploaded
	if (!$chunks || $chunk == $chunks - 1) {
		// Strip the temp .part suffix off 
		rename("{$filePath}.part", $filePath);
		DB::query(Database::INSERT, DB::insert('galeria', array('name',"cat_id"))->values(array($fileName,$ai)))->execute();
		Upload::resize("$directory/".Upload::$normal."/$fileName",160,190,NULL,200, "$directory/".Upload::$small."/$fileName");
	}

	// Return JSON-RPC response
	die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');	
	}
	
}

?>