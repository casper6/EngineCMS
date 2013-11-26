<?php
//////////////////////////////////////////////////////////////
///  php_thumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://php_thumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
error_reporting(0);
ini_set('display_errors', '0');

ini_set('magic_quotes_runtime', '0');
if (@ini_get('magic_quotes_runtime')) {
	die('"magic_quotes_runtime" is set in php.ini, cannot run php_thumb with this enabled');
}
$starttime = array_sum(explode(' ', microtime()));

// this script relies on the superglobal arrays, fake it here for old PHP versions
if (phpversion() < '4.1.0') {
	$_SERVER = $HTTP_SERVER_VARS;
	$_GET    = $HTTP_GET_VARS;
}

// instantiate a new php_thumb() object
ob_start();
if (!include_once(dirname(__FILE__).'/php_thumb.class.php')) {
	ob_end_flush();
	die('failed to include_once("'.realpath(dirname(__FILE__).'/php_thumb.class.php').'")');
}
ob_end_clean();

$php_thumb = new php_thumb();
$php_thumb->DebugTimingMessage('php_thumb.php start', __FILE__, __LINE__, $starttime);
$php_thumb->SetParameter('config_error_die_on_error', true);

if (!php_thumb_functions::FunctionIsDisabled('set_time_limit')) {
	set_time_limit(60);  // shouldn't take nearly this long in most cases, but with many filters and/or a slow server...
}

// php_thumbDebug[0] used to be here, but may reveal too much
// info when high_security_mode should be enabled (not set yet)

if (file_exists(dirname(__FILE__).'/php_thumb.config.php')) {
	ob_start();
	if (include_once(dirname(__FILE__).'/php_thumb.config.php')) {
		// great
	} else {
		ob_end_flush();
		$php_thumb->ErrorImage('failed to include_once('.dirname(__FILE__).'/php_thumb.config.php) - realpath="'.realpath(dirname(__FILE__).'/php_thumb.config.php').'"');
	}
	ob_end_clean();
} elseif (file_exists(dirname(__FILE__).'/php_thumb.config.php.default')) {
	$php_thumb->ErrorImage('Please rename "php_thumb.config.php.default" to "php_thumb.config.php"');
} else {
	$php_thumb->ErrorImage('failed to include_once('.dirname(__FILE__).'/php_thumb.config.php) - realpath="'.realpath(dirname(__FILE__).'/php_thumb.config.php').'"');
}

if (!@$php_thumb_CONFIG['disable_pathinfo_parsing'] && (empty($_GET) || isset($_GET['php_thumbDebug'])) && !empty($_SERVER['PATH_INFO'])) {
	$_SERVER['PHP_SELF'] = str_replace($_SERVER['PATH_INFO'], '', @$_SERVER['PHP_SELF']);

	$args = explode(';', substr($_SERVER['PATH_INFO'], 1));
	$php_thumb->DebugMessage('PATH_INFO.$args set to ('.implode(')(', $args).')', __FILE__, __LINE__);
	if (!empty($args)) {
		$_GET['src'] = @$args[count($args) - 1];
		$php_thumb->DebugMessage('PATH_INFO."src" = "'.$_GET['src'].'"', __FILE__, __LINE__);
		if (eregi('^new\=([a-z0-9]+)', $_GET['src'], $matches)) {
			unset($_GET['src']);
			$_GET['new'] = $matches[1];
		}
	}
	if (eregi('^([0-9]*)x?([0-9]*)$', @$args[count($args) - 2], $matches)) {
		$_GET['w'] = $matches[1];
		$_GET['h'] = $matches[2];
		$php_thumb->DebugMessage('PATH_INFO."w"x"h" set to "'.$_GET['w'].'"x"'.$_GET['h'].'"', __FILE__, __LINE__);
	}
	for ($i = 0; $i < count($args) - 2; $i++) {
		@list($key, $value) = explode('=', @$args[$i]);
		if (substr($key, -2) == '[]') {
			$array_key_name = substr($key, 0, -2);
			$_GET[$array_key_name][] = $value;
			$php_thumb->DebugMessage('PATH_INFO."'.$array_key_name.'[]" = "'.$value.'"', __FILE__, __LINE__);
		} else {
			$_GET[$key] = $value;
			$php_thumb->DebugMessage('PATH_INFO."'.$key.'" = "'.$value.'"', __FILE__, __LINE__);
		}
	}
}

if (@$php_thumb_CONFIG['high_security_enabled']) {
	if (!@$_GET['hash']) {
		$php_thumb->ErrorImage('ERROR: missing hash');
	} elseif (strlen($php_thumb_CONFIG['high_security_password']) < 5) {
		$php_thumb->ErrorImage('ERROR: strlen($php_thumb_CONFIG[high_security_password]) < 5');
	} elseif ($_GET['hash'] != md5(str_replace('&hash='.$_GET['hash'], '', $_SERVER['QUERY_STRING']).$php_thumb_CONFIG['high_security_password'])) {
		$php_thumb->ErrorImage('ERROR: invalid hash');
	}
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[0]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '0') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

// returned the fixed string if the evil "magic_quotes_gpc" setting is on
if (get_magic_quotes_gpc()) {
	// deprecated: 'err', 'file', 'goto',
	$RequestVarsToStripSlashes = array('src', 'wmf', 'down');
	foreach ($RequestVarsToStripSlashes as $key) {
		if (isset($_GET[$key])) {
			if (is_string($_GET[$key])) {
				$_GET[$key] = stripslashes($_GET[$key]);
			} else {
				unset($_GET[$key]);
			}
		}
	}
}

if (!@$_SERVER['PATH_INFO'] && !@$_SERVER['QUERY_STRING']) {
	$php_thumb->ErrorImage('php_thumb() v'.$php_thumb->php_thumb_version.'<br><a href="http://php_thumb.sourceforge.net">http://php_thumb.sourceforge.net</a><br><br>ERROR: no parameters specified');
}

if (@$_GET['src'] && isset($_GET['md5s']) && empty($_GET['md5s'])) {
	if (eregi('^(f|ht)tps?://', $_GET['src'])) {
		if ($rawImageData = php_thumb_functions::SafeURLread($_GET['src'], $error, $php_thumb->config_http_fopen_timeout, $php_thumb->config_http_follow_redirect)) {
			$md5s = md5($rawImageData);
		}
	} else {
		$SourceFilename = $php_thumb->ResolveFilenameToAbsolute($_GET['src']);
		if (is_readable($SourceFilename)) {
			$md5s = php_thumb_functions::md5_file_safe($SourceFilename);
		} else {
			$php_thumb->ErrorImage('ERROR: "'.$SourceFilename.'" cannot be read');
		}
	}
	if (@$_SERVER['HTTP_REFERER']) {
		$php_thumb->ErrorImage('&md5s='.$md5s);
	} else {
		die('&md5s='.$md5s);
	}
}

if (!empty($php_thumb_CONFIG)) {
	foreach ($php_thumb_CONFIG as $key => $value) {
		$keyname = 'config_'.$key;
		$php_thumb->setParameter($keyname, $value);
		if (!eregi('password|mysql', $key)) {
			$php_thumb->DebugMessage('setParameter('.$keyname.', '.$php_thumb->php_thumbDebugVarDump($value).')', __FILE__, __LINE__);
		}
	}
} else {
	$php_thumb->DebugMessage('$php_thumb_CONFIG is empty', __FILE__, __LINE__);
}

if (@$_GET['src'] && !@$php_thumb_CONFIG['allow_local_http_src'] && eregi('^http://'.@$_SERVER['HTTP_HOST'].'(.+)', @$_GET['src'], $matches)) {
	$php_thumb->ErrorImage('It is MUCH better to specify the "src" parameter as "'.$matches[1].'" instead of "'.$matches[0].'".'."\n\n".'If you really must do it this way, enable "allow_local_http_src" in php_thumb.config.php');
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[1]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '1') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

$parsed_url_referer = php_thumb_functions::ParseURLbetter(@$_SERVER['HTTP_REFERER']);
if ($php_thumb->config_nooffsitelink_require_refer && !in_array(@$parsed_url_referer['host'], $php_thumb->config_nohotlink_valid_domains)) {
	$php_thumb->ErrorImage('config_nooffsitelink_require_refer enabled and '.(@$parsed_url_referer['host'] ? '"'.$parsed_url_referer['host'].'" is not an allowed referer' : 'no HTTP_REFERER exists'));
}
$parsed_url_src = php_thumb_functions::ParseURLbetter(@$_GET['src']);
if ($php_thumb->config_nohotlink_enabled && $php_thumb->config_nohotlink_erase_image && eregi('^(f|ht)tps?://', @$_GET['src']) && !in_array(@$parsed_url_src['host'], $php_thumb->config_nohotlink_valid_domains)) {
	$php_thumb->ErrorImage($php_thumb->config_nohotlink_text_message);
}

if ($php_thumb->config_mysql_query) {
	if ($cid = @mysql_connect($php_thumb->config_mysql_hostname, $php_thumb->config_mysql_username, $php_thumb->config_mysql_password)) {
		if (@mysql_select_db($php_thumb->config_mysql_database, $cid)) {
			if ($result = @mysql_query($php_thumb->config_mysql_query, $cid)) {
				if ($row = @mysql_fetch_array($result)) {

					mysql_free_result($result);
					mysql_close($cid);
					$php_thumb->setSourceData($row[0]);
					unset($row);

				} else {
					mysql_free_result($result);
					mysql_close($cid);
					$php_thumb->ErrorImage('no matching data in database.');
				}
			} else {
				mysql_close($cid);
				$php_thumb->ErrorImage('Error in MySQL query: "'.mysql_error($cid).'"');
			}
		} else {
			mysql_close($cid);
			$php_thumb->ErrorImage('cannot select MySQL database: "'.mysql_error($cid).'"');
		}
	} else {
		$php_thumb->ErrorImage('cannot connect to MySQL server');
	}
	unset($_GET['id']);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[2]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '2') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

$php_thumb_DEFAULTS_DISABLEGETPARAMS = (bool) (@$php_thumb_CONFIG['cache_default_only_suffix'] && (strpos($php_thumb_CONFIG['cache_default_only_suffix'], '*') !== false));

if (!empty($php_thumb_DEFAULTS) && is_array($php_thumb_DEFAULTS)) {
	$php_thumb->DebugMessage('setting $php_thumb_DEFAULTS['.implode(';', array_keys($php_thumb_DEFAULTS)).']', __FILE__, __LINE__);
	foreach ($php_thumb_DEFAULTS as $key => $value) {
		if ($php_thumb_DEFAULTS_GETSTRINGOVERRIDE || !isset($_GET[$key])) {
			$_GET[$key] = $value;
			$php_thumb->DebugMessage('php_thumb_DEFAULTS assigning ('.$value.') to $_GET['.$key.']', __FILE__, __LINE__);
		}
	}
}

// deprecated: 'err', 'file', 'goto',
$allowedGETparameters = array('src', 'new', 'w', 'h', 'wp', 'hp', 'wl', 'hl', 'ws', 'hs', 'f', 'q', 'sx', 'sy', 'sw', 'sh', 'zc', 'bc', 'bg', 'bgt', 'fltr', 'xto', 'ra', 'ar', 'aoe', 'far', 'iar', 'maxb', 'down', 'php_thumbDebug', 'hash', 'md5s', 'sfn', 'dpi', 'sia', 'nocache');
foreach ($_GET as $key => $value) {
	if (@$php_thumb_DEFAULTS_DISABLEGETPARAMS && ($key != 'src')) {
		// disabled, do not set parameter
		$php_thumb->DebugMessage('ignoring $_GET['.$key.'] because of $php_thumb_DEFAULTS_DISABLEGETPARAMS', __FILE__, __LINE__);
	} elseif (in_array($key, $allowedGETparameters)) {
		$php_thumb->DebugMessage('setParameter('.$key.', '.$php_thumb->php_thumbDebugVarDump($value).')', __FILE__, __LINE__);
		$php_thumb->setParameter($key, $value);
	} else {
		$php_thumb->ErrorImage('Forbidden parameter: '.$key);
	}
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[3]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '3') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

//if (!@$_GET['php_thumbDebug'] && !is_file($php_thumb->sourceFilename) && !php_thumb_functions::gd_version()) {
//	if (!headers_sent()) {
//		// base64-encoded error image in GIF format
//		$ERROR_NOGD = 'R0lGODlhIAAgALMAAAAAABQUFCQkJDY2NkZGRldXV2ZmZnJycoaGhpSUlKWlpbe3t8XFxdXV1eTk5P7+/iwAAAAAIAAgAAAE/vDJSau9WILtTAACUinDNijZtAHfCojS4W5H+qxD8xibIDE9h0OwWaRWDIljJSkUJYsN4bihMB8th3IToAKs1VtYM75cyV8sZ8vygtOE5yMKmGbO4jRdICQCjHdlZzwzNW4qZSQmKDaNjhUMBX4BBAlmMywFSRWEmAI6b5gAlhNxokGhooAIK5o/pi9vEw4Lfj4OLTAUpj6IabMtCwlSFw0DCKBoFqwAB04AjI54PyZ+yY3TD0ss2YcVmN/gvpcu4TOyFivWqYJlbAHPpOntvxNAACcmGHjZzAZqzSzcq5fNjxFmAFw9iFRunD1epU6tsIPmFCAJnWYE0FURk7wJDA0MTKpEzoWAAskiAAA7';
//		header('Content-Type: image/gif');
//		echo base64_decode($ERROR_NOGD);
//	} else {
//		echo '*** ERROR: No PHP-GD support available ***';
//	}
//	exit;
//}

// check to see if file can be output from source with no processing or caching
$CanPassThroughDirectly = true;
if ($php_thumb->rawImageData) {
	// data from SQL, should be fine
} elseif (eregi('^http\://.+\.(jpe?g|gif|png)$', $php_thumb->src)) {
	// assume is ok to passthru if no other parameters specified
} elseif (!@is_file($php_thumb->sourceFilename)) {
	$php_thumb->DebugMessage('$CanPassThroughDirectly=false because !@is_file('.$php_thumb->sourceFilename.')', __FILE__, __LINE__);
	$CanPassThroughDirectly = false;
} elseif (!@is_readable($php_thumb->sourceFilename)) {
	$php_thumb->DebugMessage('$CanPassThroughDirectly=false because !@is_readable('.$php_thumb->sourceFilename.')', __FILE__, __LINE__);
	$CanPassThroughDirectly = false;
}
foreach ($_GET as $key => $value) {
	switch ($key) {
		case 'src':
			// allowed
			break;

		case 'w':
		case 'h':
			// might be OK if exactly matches original
			if (eregi('^http\://.+\.(jpe?g|gif|png)$', $php_thumb->src)) {
				// assume it is not ok for direct-passthru of remote image
				$CanPassThroughDirectly = false;
			}
			break;

		case 'php_thumbDebug':
			// handled in direct-passthru code
			break;

		default:
			// all other parameters will cause some processing,
			// therefore cannot pass through original image unmodified
			$CanPassThroughDirectly = false;
			$UnAllowedGET[] = $key;
			break;
	}
}
if (!empty($UnAllowedGET)) {
	$php_thumb->DebugMessage('$CanPassThroughDirectly=false because $_GET['.implode(';', array_unique($UnAllowedGET)).'] are set', __FILE__, __LINE__);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[4]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '4') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

function SendSaveAsFileHeaderIfNeeded() {
	if (headers_sent()) {
		return false;
	}
	global $php_thumb;
	$downloadfilename = php_thumb_functions::SanitizeFilename(@$_GET['sia'] ? $_GET['sia'] : (@$_GET['down'] ? $_GET['down'] : 'php_thumb_generated_thumbnail'.(@$_GET['f'] ? $_GET['f'] : 'jpg')));
	if (@$downloadfilename) {
		$php_thumb->DebugMessage('SendSaveAsFileHeaderIfNeeded() sending header: Content-Disposition: '.(@$_GET['down'] ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"', __FILE__, __LINE__);
		header('Content-Disposition: '.(@$_GET['down'] ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"');
	}
	return true;
}

$php_thumb->DebugMessage('$CanPassThroughDirectly="'.intval($CanPassThroughDirectly).'" && $php_thumb->src="'.$php_thumb->src.'"', __FILE__, __LINE__);
while ($CanPassThroughDirectly && $php_thumb->src) {
	// no parameters set, passthru

	if (eregi('^http\://.+\.(jpe?g|gif|png)$', $php_thumb->src)) {
		$php_thumb->DebugMessage('Passing HTTP source through directly as Location: redirect ('.$php_thumb->src.')', __FILE__, __LINE__);
		header('Location: '.$php_thumb->src);
		exit;
	}

	$SourceFilename = $php_thumb->ResolveFilenameToAbsolute($php_thumb->src);

	// security and size checks
	if ($php_thumb->getimagesizeinfo = @GetImageSize($SourceFilename)) {
		$php_thumb->DebugMessage('Direct passthru GetImageSize() returned [w='.$php_thumb->getimagesizeinfo[0].';h='.$php_thumb->getimagesizeinfo[1].';t='.$php_thumb->getimagesizeinfo[2].']', __FILE__, __LINE__);

		if (!@$_GET['w'] && !@$_GET['wp'] && !@$_GET['wl'] && !@$_GET['ws'] && !@$_GET['h'] && !@$_GET['hp'] && !@$_GET['hl'] && !@$_GET['hs']) {
			// no resizing needed
			$php_thumb->DebugMessage('Passing "'.$SourceFilename.'" through directly, no resizing required ("'.$php_thumb->getimagesizeinfo[0].'"x"'.$php_thumb->getimagesizeinfo[1].'")', __FILE__, __LINE__);
		} elseif ((($php_thumb->getimagesizeinfo[0] <= @$_GET['w']) || ($php_thumb->getimagesizeinfo[1] <= @$_GET['h'])) && ((@$_GET['w'] == $php_thumb->getimagesizeinfo[0]) || (@$_GET['h'] == $php_thumb->getimagesizeinfo[1]))) {
			// image fits into 'w'x'h' box, and at least one dimension matches exactly, therefore no resizing needed
			$php_thumb->DebugMessage('Passing "'.$SourceFilename.'" through directly, no resizing required ("'.$php_thumb->getimagesizeinfo[0].'"x"'.$php_thumb->getimagesizeinfo[1].'" fits inside "'.@$_GET['w'].'"x"'.@$_GET['h'].'")', __FILE__, __LINE__);
		} else {
			$php_thumb->DebugMessage('Not passing "'.$SourceFilename.'" through directly because resizing required (from "'.$php_thumb->getimagesizeinfo[0].'"x"'.$php_thumb->getimagesizeinfo[1].'" to "'.@$_GET['w'].'"x"'.@$_GET['h'].'")', __FILE__, __LINE__);
			break;
		}
		switch ($php_thumb->getimagesizeinfo[2]) {
			case 1: // GIF
			case 2: // JPG
			case 3: // PNG
				// great, let it through
				break;
			default:
				// browser probably can't handle format, remangle it to JPEG/PNG/GIF
				$php_thumb->DebugMessage('Not passing "'.$SourceFilename.'" through directly because $php_thumb->getimagesizeinfo[2] = "'.$php_thumb->getimagesizeinfo[2].'"', __FILE__, __LINE__);
				break 2;
		}

		$ImageCreateFunctions = array(1=>'ImageCreateFromGIF', 2=>'ImageCreateFromJPEG', 3=>'ImageCreateFromPNG');
		$theImageCreateFunction = @$ImageCreateFunctions[$php_thumb->getimagesizeinfo[2]];
		if ($php_thumb->config_disable_onlycreateable_passthru || (function_exists($theImageCreateFunction) && ($dummyImage = @$theImageCreateFunction($SourceFilename)))) {

			// great
			if (@is_resource($dummyImage)) {
				unset($dummyImage);
			}

			if (headers_sent()) {
				$php_thumb->ErrorImage('Headers already sent ('.basename(__FILE__).' line '.__LINE__.')');
				exit;
			}
			if (@$_GET['php_thumbDebug']) {
				$php_thumb->DebugTimingMessage('skipped direct $SourceFilename passthru', __FILE__, __LINE__);
				$php_thumb->DebugMessage('Would have passed "'.$SourceFilename.'" through directly, but skipping due to php_thumbDebug', __FILE__, __LINE__);
				break;
			}

			SendSaveAsFileHeaderIfNeeded();
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', @filemtime($SourceFilename)).' GMT');
			if ($contentType = php_thumb_functions::ImageTypeToMIMEtype(@$php_thumb->getimagesizeinfo[2])) {
				header('Content-Type: '.$contentType);
			}
			@readfile($SourceFilename);
			exit;

		} else {
			$php_thumb->DebugMessage('Not passing "'.$SourceFilename.'" through directly because ($php_thumb->config_disable_onlycreateable_passthru = "'.$php_thumb->config_disable_onlycreateable_passthru.'") and '.$theImageCreateFunction.'() failed', __FILE__, __LINE__);
			break;
		}

	} else {
		$php_thumb->DebugMessage('Not passing "'.$SourceFilename.'" through directly because GetImageSize() failed', __FILE__, __LINE__);
		break;
	}
	break;
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[5]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '5') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

function RedirectToCachedFile() {
	global $php_thumb, $php_thumb_CONFIG;

	$nice_cachefile = str_replace(DIRECTORY_SEPARATOR, '/', $php_thumb->cache_filename);
	$nice_docroot   = str_replace(DIRECTORY_SEPARATOR, '/', rtrim($php_thumb_CONFIG['document_root'], '/\\'));

	$parsed_url = php_thumb_functions::ParseURLbetter(@$_SERVER['HTTP_REFERER']);

	$nModified  = filemtime($php_thumb->cache_filename);

	if ($php_thumb->config_nooffsitelink_enabled && @$_SERVER['HTTP_REFERER'] && !in_array(@$parsed_url['host'], $php_thumb->config_nooffsitelink_valid_domains)) {

		$php_thumb->DebugMessage('Would have used cached (image/'.$php_thumb->thumbnailFormat.') file "'.$php_thumb->cache_filename.'" (Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT), but skipping because $_SERVER[HTTP_REFERER] ('.@$_SERVER['HTTP_REFERER'].') is not in $php_thumb->config_nooffsitelink_valid_domains ('.implode(';', $php_thumb->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);

	} elseif ($php_thumb->php_thumbDebug) {

		$php_thumb->DebugTimingMessage('skipped using cached image', __FILE__, __LINE__);
		$php_thumb->DebugMessage('Would have used cached file, but skipping due to php_thumbDebug', __FILE__, __LINE__);
		$php_thumb->DebugMessage('* Would have sent headers (1): Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT', __FILE__, __LINE__);
		if ($getimagesize = @GetImageSize($php_thumb->cache_filename)) {
			$php_thumb->DebugMessage('* Would have sent headers (2): Content-Type: '.php_thumb_functions::ImageTypeToMIMEtype($getimagesize[2]), __FILE__, __LINE__);
		}
		if (ereg('^'.preg_quote($nice_docroot).'(.*)$', $nice_cachefile, $matches)) {
			$php_thumb->DebugMessage('* Would have sent headers (3): Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])), __FILE__, __LINE__);
		} else {
			$php_thumb->DebugMessage('* Would have sent data: readfile('.$php_thumb->cache_filename.')', __FILE__, __LINE__);
		}

	} else {

		if (headers_sent()) {
			$php_thumb->ErrorImage('Headers already sent ('.basename(__FILE__).' line '.__LINE__.')');
			exit;
		}
		SendSaveAsFileHeaderIfNeeded();

		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT');
		if (@$_SERVER['HTTP_IF_MODIFIED_SINCE'] && ($nModified == strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) && @$_SERVER['SERVER_PROTOCOL']) {
			header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
			exit;
		}

		if ($getimagesize = @GetImageSize($php_thumb->cache_filename)) {
			header('Content-Type: '.php_thumb_functions::ImageTypeToMIMEtype($getimagesize[2]));
		} elseif (eregi('\.ico$', $php_thumb->cache_filename)) {
			header('Content-Type: image/x-icon');
		}
		if (!@$php_thumb_CONFIG['cache_force_passthru'] && ereg('^'.preg_quote($nice_docroot).'(.*)$', $nice_cachefile, $matches)) {
			header('Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])));
		} else {
			@readfile($php_thumb->cache_filename);
		}
		exit;

	}
	return true;
}

// check to see if file already exists in cache, and output it with no processing if it does
$php_thumb->SetCacheFilename();
if (@is_file($php_thumb->cache_filename)) {
	RedirectToCachedFile();
} else {
	$php_thumb->DebugMessage('Cached file "'.$php_thumb->cache_filename.'" does not exist, processing as normal', __FILE__, __LINE__);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[6]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '6') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

if ($php_thumb->rawImageData) {

	// great

} elseif (@$_GET['new']) {

	// generate a blank image resource of the specified size/background color/opacity
	if (($php_thumb->w <= 0) || ($php_thumb->h <= 0)) {
		$php_thumb->ErrorImage('"w" and "h" parameters required for "new"');
	}
	@list($bghexcolor, $opacity) = explode('|', $_GET['new']);
	if (!php_thumb_functions::IsHexColor($bghexcolor)) {
		$php_thumb->ErrorImage('BGcolor parameter for "new" is not valid');
	}
	$opacity = (strlen($opacity) ? $opacity : 100);
	if ($php_thumb->gdimg_source = php_thumb_functions::ImageCreateFunction($php_thumb->w, $php_thumb->h)) {
		$alpha = (100 - min(100, max(0, $opacity))) * 1.27;
		if ($alpha) {
			$php_thumb->setParameter('is_alpha', true);
			ImageAlphaBlending($php_thumb->gdimg_source, false);
			ImageSaveAlpha($php_thumb->gdimg_source, true);
		}
		$new_background_color = php_thumb_functions::ImageHexColorAllocate($php_thumb->gdimg_source, $bghexcolor, false, $alpha);
		ImageFilledRectangle($php_thumb->gdimg_source, 0, 0, $php_thumb->w, $php_thumb->h, $new_background_color);
	} else {
		$php_thumb->ErrorImage('failed to create "new" image ('.$php_thumb->w.'x'.$php_thumb->h.')');
	}

} elseif (!$php_thumb->src) {

	$php_thumb->ErrorImage('Usage: '.$_SERVER['PHP_SELF'].'?src=/path/and/filename.jpg'."\n".'read Usage comments for details');

} elseif (eregi('^(f|ht)tp\://', $php_thumb->src)) {

	$php_thumb->DebugMessage('$php_thumb->src ('.$php_thumb->src.') is remote image, attempting to download', __FILE__, __LINE__);
	if ($php_thumb->config_http_user_agent) {
		$php_thumb->DebugMessage('Setting "user_agent" to "'.$php_thumb->config_http_user_agent.'"', __FILE__, __LINE__);
		ini_set('user_agent', $php_thumb->config_http_user_agent);
	}
	$cleanedupurl = php_thumb_functions::CleanUpURLencoding($php_thumb->src);
	$php_thumb->DebugMessage('CleanUpURLencoding('.$php_thumb->src.') returned "'.$cleanedupurl.'"', __FILE__, __LINE__);
	$php_thumb->src = $cleanedupurl;
	unset($cleanedupurl);
	if ($rawImageData = php_thumb_functions::SafeURLread($php_thumb->src, $error, $php_thumb->config_http_fopen_timeout, $php_thumb->config_http_follow_redirect)) {
		$php_thumb->DebugMessage('SafeURLread('.$php_thumb->src.') succeeded'.($error ? ' with messsages: "'.$error.'"' : ''), __FILE__, __LINE__);
		$php_thumb->DebugMessage('Setting source data from URL "'.$php_thumb->src.'"', __FILE__, __LINE__);
		$php_thumb->setSourceData($rawImageData, urlencode($php_thumb->src));
	} else {
		$php_thumb->ErrorImage($error);
	}
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[7]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '7') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

$php_thumb->GenerateThumbnail();

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[8]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '8') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

if ($php_thumb->config_allow_parameter_file && $php_thumb->file) {

	$php_thumb->RenderToFile($php_thumb->ResolveFilenameToAbsolute($php_thumb->file));
	if ($php_thumb->config_allow_parameter_goto && $php_thumb->goto && eregi('^(f|ht)tps?://', $php_thumb->goto)) {
		// redirect to another URL after image has been rendered to file
		header('Location: '.$php_thumb->goto);
		exit;
	}

} elseif (@$php_thumb_CONFIG['high_security_enabled'] && @$_GET['nocache']) {

	// cache disabled, don't write cachefile

} else {

	php_thumb_functions::EnsureDirectoryExists(dirname($php_thumb->cache_filename));
	if ((file_exists($php_thumb->cache_filename) && is_writable($php_thumb->cache_filename)) || is_writable(dirname($php_thumb->cache_filename))) {

		$php_thumb->CleanUpCacheDirectory();
		if ($php_thumb->RenderToFile($php_thumb->cache_filename) && is_readable($php_thumb->cache_filename)) {
			chmod($php_thumb->cache_filename, 0644);
			RedirectToCachedFile();
		} else {
			$php_thumb->DebugMessage('Failed: RenderToFile('.$php_thumb->cache_filename.')', __FILE__, __LINE__);
		}

	} else {

		$php_thumb->DebugMessage('Cannot write to $php_thumb->cache_filename ('.$php_thumb->cache_filename.') because that directory ('.dirname($php_thumb->cache_filename).') is not writable', __FILE__, __LINE__);

	}

}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[9]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '9') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

if (!$php_thumb->OutputThumbnail()) {
	$php_thumb->ErrorImage('Error in OutputThumbnail():'."\n".$php_thumb->debugmessages[(count($php_thumb->debugmessages) - 1)]);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$php_thumb->DebugTimingMessage('php_thumbDebug[10]', __FILE__, __LINE__);
if (@$_GET['php_thumbDebug'] == '10') {
	$php_thumb->php_thumbDebug();
}
////////////////////////////////////////////////////////////////

?>