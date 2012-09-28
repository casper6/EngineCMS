<?
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "config.php");
if (!function_exists("stripos")) 
{
  function stripos($str,$needle,$offset=0)
  {
      return strpos(strtolower($str),strtolower($needle),$offset);
  }
}
/**
 * Removes illegal characters from a filename and returns the cleaned one.
 *
 * @param String $filename Name of file to check
 * @return String a filename containing only allowed characters
 */
function cleanFilename($filename) {
	$charLookup = array(
		"" => "a",
		"" => "a",
		"" => "o",
		"" => "a",
		"" => "a",
		"" => "o",
		" " => "_"
	);

	$filename = strtolower($filename);
	$filename = strtr($filename, $charLookup);
	$strlen = strlen($filename);

	for ($i=0;$i<=$strlen;$i++) {
		$chr = substr($filename, $i, 1);
		$ord = ord($chr);

		if ( ( ($ord >= ord('0')) AND ($ord <= ord('9')) ) OR ( ($ord >= ord('a')) AND ($ord <= ord('z')) ) OR (ord('_') == $ord ) )
			$outstr .= $chr;
	}

	return $outstr;
}
/**
 * print out an array
 *
 * @param array $array
 */
function displayArray($array, $comments="")
{
	echo "<pre>";
	echo $comments;
	print_r($array);
	echo $comments;
	echo "</pre>";
}
/**
 * Checks for an already existing file with the same name, and
 * renames the active file to a unique name if one is found.
 *
 * @param String $path Path of file
 * @param String $filename Name of file
 * @return String A unique filename.
 */
function getUniqueFilename($path, $filename) {
	if (file_exists($path . "/" . $filename)) {
		$ar = explode('.', $filename);
		$fileext  = array_pop($ar);
		$basename = basename($filename, '.'.$fileext);
		$instance = 2;

		while(file_exists($path . "/" . $basename . "_" . $instance . "." . $fileext))
			$instance++;

		return $basename . "_" . $instance . "." . $fileext;
	}

	return $filename;
}
/**
 * Returns a filesize as a nice truncated string like "10.3 MB".
 *
 * @param int $size File size to convert.
 * @return String Nice truncated string of the file size.
 */
function getSizeStr($size) {
	// MB
	if ($size > 1048576)
		return round($size / 1048576, 1) . " MB";

	// KB
	if ($size > 1024)
		return round($size / 1024, 1) . " KB";

	if ($size == "")
		return "";

	return $size . " b";
}

/**
 * Returns the file type of a file.
 *
 * @param String file name
 * @return Array Array with file type info.
 */
function getFileType($file_name) {
	global $manager;
	$fileTypes = $manager->getFileTypes();
	$ar = explode('.', $file_name);
	$ext = strtolower(array_pop($ar));

	// Search for extension
	foreach ($fileTypes as $type) {
		foreach ($type[0] as $targetExt) {
			if ($ext == $targetExt)
				return array("cssClass" => $type[1], "fileType" => $type[2], "preview" => $type[3]);
		}
	}
	// Not in list
	if(strpos($file_name, ".") === false)
	{//Folder
		return array("cssClass" => "folder", "fileType" => "Folder", "preview" => 0);
	}else
	{
		return array("cssClass" => "fileUnknown", "fileType" => "Normal file", "preview" => 0);
	}


}

/**
 * Returns the script name.
 *
 * @return String script name.
 */
function getScriptName() {
	$arrayShifter = "";

	if (isset($_SERVER["PHP_SELF"])) {
		$arrayShifter = explode(".", basename($_SERVER["PHP_SELF"]));
		return array_shift($arrayShifter);
	}

	if (isset($_SERVER["SCRIPT_NAME"])) {
		$arrayShifter = explode(".", basename($_SERVER["SCRIPT_NAME"]));
		return array_shift($arrayShifter);
	}
}

/**
 * Returns the wwwroot or null string if it was impossible to get.
 *
 * @return String wwwroot or null string if it was impossible to get.
 */
function getWWWRoot() {
		$output = "";
		if (defined('CONFIG_URL_PREVIEW_ROOT') && CONFIG_URL_PREVIEW_ROOT)
		{
			return toOSPath($urlDocBase);
		}
		if(isset($_SERVER['DOCUMENT_ROOT']) && ($output = resolvePath($_SERVER['DOCUMENT_ROOT'])) != '' )
		{
			return $output;
		}elseif(isset($_SERVER["SCRIPT_NAME"]) && isset($_SERVER["SCRIPT_FILENAME"]) && ($output = str_replace(toUnixPath($_SERVER["SCRIPT_NAME"]), "", toUnixPath($_SERVER["SCRIPT_FILENAME"]))) && is_dir($output))
		{
			return toOSPath($output);
		}elseif
		(isset($_SERVER["SCRIPT_NAME"]) && isset($_SERVER["PATH_TRANSLATED"]) && ($output = str_replace(toUnixPath($_SERVER["SCRIPT_NAME"]), "", str_replace("//", "/", toUnixPath($_SERVER["PATH_TRANSLATED"])))) && is_dir($output))
		{
			return $output;
		}else 
		{
			return '';
		}	
	
	// Check document root

	return null;
}
/**
 * Returns the absolute path of a path
 *
 * @param String $path

 */
function getRealPath($path) {
	return resolvePath($path);
}




/**
 * Resolves relative path to absolute path. The output path is in unix format.
 */
function resolvePath($path, $verify = true) {
	$result = realpath($path);

	$result = preg_replace("/(\\\\)/","\\", $result);

	if ($result == "" && $verify)
		//trigger_error("Check your rootpath & path config (or other paths), could not resolve path: \"". $path . "\".", FATAL);

	return toUnixPath($result);
}

/**
 * Converts a Unix path to OS specific path.
 *
 * @param String $path Unix path to convert.
 */
function toOSPath($path) {
	return str_replace("/", DIRECTORY_SEPARATOR, $path);
}

/**
 * Converts a OS specific path to Unix path.
 *
 * @param String $path OS path to convert to Unix style.
 */
function toUnixPath($path) {
	return str_replace(DIRECTORY_SEPARATOR, "/", $path);
}

/**
 * Removes the trailing slash from a path.
 *
 * @param String path Path to remove trailing slash from.
 * @return String New path without trailing slash.
 */
function removeTrailingSlash($path) {
	// Is root
	if ($path == "/")
		return $path;

	if ($path == "")
		return $path;

	if ($path[strlen($path)-1] == '/')
		$path = substr($path, 0, strlen($path)-1);

	return $path;
}

/**
 * Adds a trailing slash to a path.
 *
 * @param String path Path to add trailing slash on.
 * @return String New path with trailing slash.
 */
function addTrailingSlash($path) {
	if (strlen($path) > 0 && $path[strlen($path)-1] != '/')
		$path .= '/';
	return $path;
}

/**
 * Returns the user path, the path that the users sees.
 *
 * @param String $path Absolute file path.
 * @return String Visual path, user friendly path.
 */
function getUserFriendlyPath($path, $max_len = -1) {
	$rootPath = toUnixPath(addTrailingSlash(realpath(CONFIG_SYS_ROOT_PATH)));
	$path = toUnixPath(addTrailingSlash(realpath($path)));
	if($rootPath)
	{
		
		$pos = strpos($path, $rootPath);
		if ($pos !== false && $pos == 0)
		{
			$path  = substr($path, strlen($rootPath));
		}else
		{
			$path = "/";
		}
		
	}
		if (strlen($path) > 0 && $path[0] != '/')
			$path = "/" . $path;
		if(strlen($path) < 1)
		{
			$path = "/" . $path;
		}	

$path = str_replace("//","/",$path);
	return $path;
}

/**
 * Returns a absolute path from a virtual path.
 *
 * @param String $path Virtual path to map.
 * @return String Returns a absolute path from a virtual path.
 */
function getAbsPath($path) {
	if (substr($path, 0, 1) == "/")
		return toOSPath(DIR_AJAX_ROOT . $path);

	return toOSPath(dirname(__FILE__) . "/" . $path);
}
/**
 * Adds no cache headers to HTTP response.
 */
function addNoCacheHeaders() {
	// Date in the past
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

	// always modified
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

	// HTTP/1.1
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);

	// HTTP/1.0
	header("Pragma: no-cache");
}
	/**
	 * add extra query stiring to a url
	 * @param string $baseUrl
	 * @param string $extra the query string added to the base url
	 */
	function appendQueryString($baseUrl, $extra)
	{
		$output = $baseUrl;
		if(strpos($baseUrl, "?") !== false)
		{
			$output .= "&" . $extra;
		}else
		{
			$output .= "?" . $extra;
		}
		return $output;
	}
	/**
	 * get parent path from specific path
	 *
	 * @param string $path
	 * @return string
	 */
	function getParentPath($path)
	{
		$path = removeTrailingSlash($path);
		if(false !== ($index = strrpos($path, "/")) )
		{
			return substr($path, 0, $index);
		}

	}
	/**
	 * get file/folder base name
	 *
	 * @param string $path
	 * @return string
	 */
	function getBaseName($path)
	{
		$path = removeTrailingSlash($path);

		if(false !== ($index = strrpos($path, "/")) )
		{
			return substr($path, $index + 1);
		}else
		{
			return $path;
		}
	}
	/**
	 * check if the file/folder is sit under the root
	 *
	 * @param string $path
	 * @return  boolean
	 */
	function isUnderRoot($path)
	{
		$roorPath = strtolower(toUnixPath(addTrailingSlash(CONFIG_SYS_ROOT_PATH)));
		if(file_exists($path) && strpos(strtolower(toUnixPath(addTrailingSlash($path))), $roorPath) === 0 )
		{
			return true;
		}
		return false;
	}
	
/**
 * Verifies that a path is within the parent path.
 */
function isChildPath($parent_paths, $path) {
	// Is child of any of the specified paths
	if (is_array($parent_paths)) {
		foreach ($parent_paths as $key => $validPath) {
			if (strpos(strtolower(addTrailingSlash($path)), strtolower(addTrailingSlash($validPath))) === 0)
				return $validPath;
		}
		return false;
	}
	return strpos(strtolower(addTrailingSlash($path)), strtolower(addTrailingSlash($parent_paths))) === 0;
}	
	/**
	 * get thumbnail width and height
	 *
	 * @param integer $originaleImageWidth
	 * @param integer $originalImageHeight
	 * @param integer $thumbnailWidth
	 * @param integer $thumbnailHeight
	 * @return array()
	 */
	function getThumbWidthHeight( $originaleImageWidth, $originalImageHeight, $thumbnailWidth, $thumbnailHeight)
	{
		$outputs = array( "width"=>0, "height"=>0);
		$thumbnailWidth	= intval($thumbnailWidth);
		$thumbnailHeight = intval($thumbnailHeight);
		if(!empty($originaleImageWidth) && !empty($originalImageHeight))
		{
			//start to get the thumbnail width & height
        	if(($thumbnailWidth < 1 && $thumbnailHeight < 1) || ($thumbnailWidth > $originaleImageWidth && $thumbnailHeight > $originalImageHeight ))
        	{
        		$thumbnailWidth =$originaleImageWidth;
        		$thumbnailHeight = $originalImageHeight;
        	}elseif($thumbnailWidth < 1)
        	{
        		$thumbnailWidth = floor($thumbnailHeight / $originalImageHeight * $originaleImageWidth);

        	}elseif($thumbnailHeight < 1)
        	{
        		$thumbnailHeight = floor($thumbnailWidth / $originaleImageWidth * $originalImageHeight);
        	}else
        	{
        		$scale = min($thumbnailWidth/$originaleImageWidth, $thumbnailHeight/$originalImageHeight);
				$thumbnailWidth = floor($scale*$originaleImageWidth);
				$thumbnailHeight = floor($scale*$originalImageHeight);
        	}
			$outputs['width'] = $thumbnailWidth;
			$outputs['height'] = $thumbnailHeight;
		}
		return $outputs;

	}

	/**
	 * get file url
	 *
	 * @param string $path
	 * @return string
	 */
	function getFileUrl($path)
	{
		$output = '';
		$wwwroot = removeTrailingSlash(toUnixPath(getWWWRoot()));
		$urlprefix = "";
		$urlsuffix = "";
		$path = toUnixPath(realpath($path));
		$pos = stripos($path, $wwwroot);
		if ($pos !== false && $pos == 0)
		{
			$output  = $urlprefix . substr($path, strlen($wwwroot)) . $urlsuffix;
		}else 
		{
			$output = $path;
		}
		//$output .= "path: " . $path . "; www:" . $wwwroot;
		return "http://" .  addTrailingSlash($_SERVER['HTTP_HOST']) . removeBeginingSlash($output);
	}
	/**
	 * remove beginging slash
	 *
	 * @param string $path
	 * @return string
	 */
	function removeBeginingSlash($path)
	{
		if(strpos($path, "/") === 0)
		{
			$path = substr($path, 1);
		}
		return $path;
	}
	/**
	 * add beginging slash
	 *
	 * @param string $path
	 * @return string
	 */	
	function addBeginingSlash($path)
	{
		if(strpos($path, "/") !== 0 && !empty($path))
		{
			$path .= "/" . $path;
		}
		return $path;		
	}

	function getRelativeFileUrl($path, $relativeTo)
	{
		$output = '';
		$wwwroot = removeTrailingSlash(toUnixPath(getWWWRoot()));
		$urlprefix = "";
		$urlsuffix = "";
		$path = toUnixPath(realpath($path));
		$pos = strpos($path, $wwwroot);
		if ($pos !== false && $pos == 0)
		{
			$output  = $urlprefix . substr($path, strlen($wwwroot)) . $urlsuffix;
		}
	}
	
	function writeInfo($data)
	{
		$fp = @fopen(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data.php', 'w+');
		@fwrite($fp, $data);
		@fwrite($fp, "\n\n" . date('d/M/Y H:i:s') );
		@fclose($fp);
		
	}
	
	/**
	 * get a file extension
	 *
	 * @param string $fileName the path to a file or just the file name
	 */	
	function getFileExt($filePath)
	{
		return @substr(@strrchr($filePath, "."), 1);
	}
	/**
	 * check if a file extension is permitted
	 *
	 * @param string $filePath
	 * @param array $validExts
	 * @param array $invalidExts
	 * @return boolean
	 */
	function isValidExt($filePath, $validExts, $invalidExts=array())
	{
		$tem = array();

		if(sizeof($validExts))
		{
			foreach($validExts as $k=>$v)
			{
				$tem[$k] = trim($v);
			}
		}
		$validExts = $tem;

		if(sizeof($validExts) && sizeof($invalidExts))
		{
			foreach($validExts as  $k=>$ext)
			{
				if(array_search($ext, $invalidExts) !== false)
				{
					unset($validExts[$k]);
				}
			}
		}
		if(sizeof($validExts))
		{
			if(array_search(getFileExt($filePath), $validExts) !== false)
			{
				return true;
			}
		}elseif(array_search(getFileExt($filePath), $invalidExts) === false)
		{
			return true;
		}else 
		{
			return false;
		}
	}

?>