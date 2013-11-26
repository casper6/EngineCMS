<?php
//////////////////////////////////////////////////////////////
///  php_thumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://php_thumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// See: php_thumb.readme.txt for usage instructions          //
//      NOTE: THIS FILE HAS NO EFFECT IN OBJECT MODE!       //
//            THIS CONFIG FILE ONLY APPLIES TO php_thumb.php //
//                                                         ///
//////////////////////////////////////////////////////////////

ob_start();
if (!file_exists(dirname(__FILE__).'/php_thumb.functions.php') || !include_once(dirname(__FILE__).'/php_thumb.functions.php')) {
	ob_end_flush();
	die('failed to include_once(php_thumb.functions.php) - realpath="'.realpath(dirname(__FILE__).'/php_thumb.functions.php').'"');
}
ob_end_clean();

// START USER CONFIGURATION SECTION:

// * DocumentRoot configuration
// php_thumb() depends on $_SERVER['DOCUMENT_ROOT'] to resolve path/filenames. This value is usually correct,
// but has been known to be broken on some servers. This value allows you to override the default value.
// Do not modify from the auto-detect default value unless you are having problems.
//$php_thumb_CONFIG['document_root'] = '/home/httpd/httpdocs';
//$php_thumb_CONFIG['document_root'] = 'c:\\webroot\\example.com\\www';
//$php_thumb_CONFIG['document_root'] = $_SERVER['DOCUMENT_ROOT'];
//$php_thumb_CONFIG['document_root'] = realpath((@$_SERVER['DOCUMENT_ROOT'] && file_exists(@$_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])) ? $_SERVER['DOCUMENT_ROOT'] : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', realpath('.'))));
$php_thumb_CONFIG['document_root'] = realpath((getenv('DOCUMENT_ROOT') && ereg('^'.preg_quote(realpath(getenv('DOCUMENT_ROOT'))), realpath(__FILE__))) ? getenv('DOCUMENT_ROOT') : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__))));

// * Cache directory configuration (choose only one of these - leave the other lines commented-out):
// Note: this directory must be writable (usually chmod 777 is neccesary) for caching to work.
// If the directory is not writable no error will be generated but caching will be disabled.
$php_thumb_CONFIG['cache_directory'] = dirname(__FILE__).'/cache/';                            // set the cache directory relative to the php_thumb() installation
//$php_thumb_CONFIG['cache_directory'] = $php_thumb_CONFIG['document_root'].'/php_thumb/cache/'; // set the cache directory to an absolute directory for all source images
//$php_thumb_CONFIG['cache_directory'] = './cache/';                                           // set the cache directory relative to the source image - must start with '.' (will not work to cache URL- or database-sourced images, please use an absolute directory name)
//$php_thumb_CONFIG['cache_directory'] = null;                                                 // disable thumbnail caching (not recommended)
//if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
//	$php_thumb_CONFIG['cache_directory'] = dirname(__FILE__).'/cache/'; // set the cache directory to an absolute directory for all source images
//} else {
//	$php_thumb_CONFIG['cache_directory'] = '/tmp/persistent/php_thumb/cache/';
//}

$php_thumb_CONFIG['cache_disable_warning'] = true; // If [cache_directory] is non-existant or not writable, and [cache_disable_warning] is false, an error image will be generated warning to either set the cache directory or disable the warning (to avoid people not knowing about the cache)

$php_thumb_CONFIG['cache_directory_depth'] = 2; // If this larger than zero, cache structure will be broken into a broad directory structure based on cache filename. For example "cache_src012345..." will be stored in "/0/01/012/0123/cache_src012345..." when (cache_directory_depth = 4)


// * Cache culling: php_thumb can automatically limit the contents of the cache directory
//   based on last-access date and/or number of files and/or total filesize.

//$php_thumb_CONFIG['cache_maxage'] = null;            // never delete cached thumbnails based on last-access time
$php_thumb_CONFIG['cache_maxage'] = 20160 * 30;        // delete cached thumbnails that haven't been accessed in more than [7 days] (value is maximum time since last access in seconds to avoid deletion)

//$php_thumb_CONFIG['cache_maxsize'] = null;           // never delete cached thumbnails based on byte size of cache directory
$php_thumb_CONFIG['cache_maxsize'] = 10 * 1024 * 1024; // delete least-recently-accessed cached thumbnails when more than [10MB] of cached files are present (value is maximum bytesize of all cached files)

//$php_thumb_CONFIG['cache_maxfiles'] = null;          // never delete cached thumbnails based on number of cached files
$php_thumb_CONFIG['cache_maxfiles'] = 500;             // delete least-recently-accessed cached thumbnails when more than [500] cached files are present (value is maximum number of cached files to keep)


// * Source image cache configuration
$php_thumb_CONFIG['cache_source_enabled']   = false;                               // if true, source images obtained via HTTP are cached to $php_thumb_CONFIG['cache_source_directory']
$php_thumb_CONFIG['cache_source_directory'] = dirname(__FILE__).'/cache/source/';  // set the cache directory for unprocessed source images

// * cache source modification date configuration
$php_thumb_CONFIG['cache_source_filemtime_ignore_local']  = false; // if true, local source images will not be checked for modification date and cached image will be used if available, even if source image is changed or removed
$php_thumb_CONFIG['cache_source_filemtime_ignore_remote'] = true;  // if true, remote source images will not be checked for modification date and cached image will be used if available, even if source image is changed or removed. WARNING: cached performance MUCH slower if this is set to false.


// * Simplified cache filename configuration
// Instead of creating unique cache filenames for all parameter combinations, create "simple" cache files (eg: "pic_thumb.jpg")
// If cache_default_only_suffix is non-empty, GETstring parameters (except 'src') are ignored and only $php_thumb_DEFAULTS
// parameters (set at the bottom of php_thumb.config.php) are used for processing.
// The '*' character MUST be used to represent the source image name
$php_thumb_CONFIG['cache_default_only_suffix'] = '';           // cached in normal php_thumb manner
//$php_thumb_CONFIG['cache_default_only_suffix'] = '*_thumb';  // cache 'pic.jpg' becomes 'pic_thumb.jpg' (or 'pic_thumb.png' if PNG output is selected, etc)
//$php_thumb_CONFIG['cache_default_only_suffix'] = 'small-*';  // cache 'pic.jpg' becomes 'small-pic.jpg' (or 'small-pic.png' if PNG output is selected, etc)

$php_thumb_CONFIG['cache_prefix'] = 'php_thumb_cache_'.str_replace('www.', '', @$_SERVER['SERVER_NAME']);
//$php_thumb_CONFIG['cache_prefix'] = 'php_thumb_cache';                         // allow php_thumb to share 1 set of cached files even if accessed under different servername/domains on same server

$php_thumb_CONFIG['cache_force_passthru'] = true;  // if true, cached image data will always be passed to browser; if false, HTTP redirect will be used instead



// * Temp directory configuration
// php_thumb() may need to create temp files. Usually the system temp dir is writable and can be used.
// Leave this value as NULL in most cases. If you get errors about "failed to open <filename> for writing"
// you should change this to a full pathname to a directory you do have write access to.
//$php_thumb_CONFIG['temp_directory'] = null;                               // attempt to auto-detect
//$php_thumb_CONFIG['temp_directory'] = '/tmp/persistent/php_thumb/cache/';  // set to absolute path
$php_thumb_CONFIG['temp_directory'] = $php_thumb_CONFIG['cache_directory'];  // set to same as cache directory


// NOTE: "max_source_pixels" only affects GD-resized thumbnails. If you have ImageMagick
//       installed it will bypass most of these limits
// maximum number of pixels in source image to attempt to process entire image in GD mode.
// If this is zero then no limit on source image dimensions.
// If this is nonzero then this is the maximum number of pixels the source image
// can have to be processed normally, otherwise the embedded EXIF thumbnail will
// be used (if available) or an "image too large" notice will be displayed.
// This is to be used for large source images (> 1600x1200) and low PHP memory
// limits. If PHP runs out of memory the script will usually just die with no output.
// To calculate this number, multiply the dimensions of the largest image
// you can process with your memory limitation (e.g. 1600 * 1200 = 1920000)
// As a general guideline, this number will be about 20% of your PHP memory
// configuration, so 8M = 1,677,722; 16M = 3,355,443; 32M = 6,710,886; etc.
if (php_thumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=') && !defined('memory_get_usage') && !@ini_get('memory_limit')) {
	// memory_get_usage() will only be defined if your PHP is compiled with the --enable-memory-limit configuration option.
	$php_thumb_CONFIG['max_source_pixels'] = 0;         // no memory limit
} else {
	// calculate default max_source_pixels as 1/6 of memory limit configuration
	$php_thumb_CONFIG['max_source_pixels'] = round(max(intval(ini_get('memory_limit')), intval(get_cfg_var('memory_limit'))) * 1048576 / 6);
	//$php_thumb_CONFIG['max_source_pixels'] = 0;       // no memory limit
	//$php_thumb_CONFIG['max_source_pixels'] = 1920000; // allow 1600x1200 images (2Mpx), no larger (about 12MB memory required)
	//$php_thumb_CONFIG['max_source_pixels'] = 2795000; // 16MB memory limit
	//$php_thumb_CONFIG['max_source_pixels'] = 3871488; // allow 2272x1704 images (4Mpx), no larger (about 24MB memory required)
}


// ImageMagick configuration
$php_thumb_CONFIG['prefer_imagemagick']        = true;  // If true, use ImageMagick to resize thumbnails if possible, since it is usually faster than GD functions; if false only use ImageMagick if PHP memory limit is too low.
$php_thumb_CONFIG['imagemagick_use_thumbnail'] = true;  // If true, use ImageMagick's "-thumbnail" resizing parameter (if available) which removes extra non-image metadata (profiles, EXIF info, etc) resulting in much smaller filesize; if false, use "-resize" paramter which retains this info
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
	// Windows: set absolute pathname
	$php_thumb_CONFIG['imagemagick_path'] = 'C:/ImageMagick/convert.exe';
} else {
	// *nix: set absolute pathname to "convert", or leave as null if "convert" is in the path (location detected with `which`)
	//$php_thumb_CONFIG['imagemagick_path'] = '/usr/local/bin/convert';
	$php_thumb_CONFIG['imagemagick_path'] = null;
}



// * Default output configuration:
$php_thumb_CONFIG['output_format']    = 'jpeg'; // default output format ('jpeg', 'png' or 'gif') - thumbnail will be output in this format (if available in your version of GD or ImageMagick). This is only used if the "f" parameter is not specified, and if the thumbnail can't be output in the input format.
$php_thumb_CONFIG['output_maxwidth']  = 0;      // default maximum thumbnail width.  If this is zero then default width  is the width  of the source image. This is always overridden by ?w=___ GETstring parameter
$php_thumb_CONFIG['output_maxheight'] = 0;      // default maximum thumbnail height. If this is zero then default height is the height of the source image. This is always overridden by ?h=___ GETstring parameter
$php_thumb_CONFIG['output_interlace'] = true;   // if true: interlaced output for GIF/PNG, progressive output for JPEG; if false: non-interlaced for GIF/PNG, baseline for JPEG.

// * Error message configuration
$php_thumb_CONFIG['error_image_width']           = 100;      // default width for error images
$php_thumb_CONFIG['error_image_height']          = 100;      // default height for error images
$php_thumb_CONFIG['error_message_image_default'] = '';       // Set this to the name of a generic error image (e.g. '/images/error.png') that you want displayed in place of any error message that may occur. This setting is overridden by the 'err' parameter, which does the same thing.
$php_thumb_CONFIG['error_bgcolor']               = 'CCCCFF'; // background color of error message images
$php_thumb_CONFIG['error_textcolor']             = 'FF0000'; // color of text in error messages
$php_thumb_CONFIG['error_fontsize']              = 1;        // size of text in error messages, from 1 (smallest) to 5 (largest)
$php_thumb_CONFIG['error_die_on_error']          = true;     // die with error message on any fatal error (recommended with standalone php_thumb.php)
$php_thumb_CONFIG['error_silent_die_on_error']   = false;    // simply die with no output of any kind on fatal errors (not recommended)
$php_thumb_CONFIG['error_die_on_source_failure'] = true;     // die with error message if source image cannot be processed by php_thumb() (usually because source image is corrupt in some way). If false the source image will be passed through unprocessed, if true (default) an error message will be displayed.

// * Off-server Thumbnailing Configuration:
$php_thumb_CONFIG['nohotlink_enabled']           = false;                                    // If false will allow thumbnailing from any source domain
$php_thumb_CONFIG['nohotlink_valid_domains']     = array(@$_SERVER['HTTP_HOST']);            // This is the list of domains for which thumbnails are allowed to be created. Note: domain only, do not include port numbers. The default value of the current domain should be fine in most cases, but if neccesary you can add more domains in here, in the format "www.example.com"
$php_thumb_CONFIG['nohotlink_erase_image']       = true;                                     // if true thumbnail is covered up with $php_thumb_CONFIG['nohotlink_fill_color'] before text is applied, if false text is written over top of thumbnail
$php_thumb_CONFIG['nohotlink_text_message']      = 'Off-server thumbnailing is not allowed'; // text of error message

// * Off-server Linking Configuration:
$php_thumb_CONFIG['nooffsitelink_enabled']       = false;                                       // If false will allow thumbnails to be linked to from any domain, if true only domains listed below in 'nooffsitelink_valid_domains' will be allowed.
$php_thumb_CONFIG['nooffsitelink_valid_domains'] = array(@$_SERVER['HTTP_HOST']);              // This is the list of domains for which thumbnails are allowed to be created. The default value of the current domain should be fine in most cases, but if neccesary you can add more domains in here, in the format 'www.example.com'
$php_thumb_CONFIG['nooffsitelink_require_refer'] = false;                                      // If false will allow standalone calls to php_thumb(). If true then only requests with a $_SERVER['HTTP_REFERER'] value in 'nooffsitelink_valid_domains' are allowed.
$php_thumb_CONFIG['nooffsitelink_erase_image']   = false;                                      // if true thumbnail is covered up with $php_thumb_CONFIG['nohotlink_fill_color'] before text is applied, if false text is written over top of thumbnail
$php_thumb_CONFIG['nooffsitelink_watermark_src'] = '/images/watermark.png';                // webroot-relative image to overlay on hotlinked images
$php_thumb_CONFIG['nooffsitelink_text_message']  = 'Фото взято с '.@$_SERVER['HTTP_HOST']; // text of error message (used if [nooffsitelink_watermark_src] is not a valid image)


// * Border & Background default colors
$php_thumb_CONFIG['border_hexcolor']     = '000000'; // Default border color - usual HTML-style hex color notation (overidden with 'bc' parameter)
$php_thumb_CONFIG['background_hexcolor'] = 'FFFFFF'; // Default background color when thumbnail aspect ratio does not match fixed-dimension box - usual HTML-style hex color notation (overridden with 'bg' parameter)

// * Watermark configuration
$php_thumb_CONFIG['ttf_directory'] = dirname(__FILE__).'/fonts'; // Base directory for TTF font files
//$php_thumb_CONFIG['ttf_directory'] = 'c:/windows/fonts';


// * MySQL configuration
// You may want to pull data from a database rather than a physical file
// If so, modify the $php_thumb_CONFIG['mysql_query'] line to suit your database structure
// Note: the data retrieved must be the actual binary data of the image, not a URL or filename

$php_thumb_CONFIG['mysql_query'] = '';
//$php_thumb_CONFIG['mysql_query'] = 'SELECT `picture` FROM `products` WHERE (`id` = \''.mysql_escape_string(@$_GET['id']).'\')';

// These 4 values must be modified if $php_thumb_CONFIG['mysql_query'] is not empty, but may be ignored if $php_thumb_CONFIG['mysql_query'] is blank.
$php_thumb_CONFIG['mysql_hostname'] = 'localhost';
$php_thumb_CONFIG['mysql_username'] = '';
$php_thumb_CONFIG['mysql_password'] = '';
$php_thumb_CONFIG['mysql_database'] = '';


// * Security configuration
$php_thumb_CONFIG['high_security_enabled']    = false;  // if enabled, requires 'high_security_password' set to at least 5 characters, and requires the use of php_thumbURL() function (at the bottom of php_thumb.config.php) to generate hashed URLs
$php_thumb_CONFIG['high_security_password']   = '';     // required if 'high_security_enabled' is true, must be at least 5 characters long
$php_thumb_CONFIG['disable_debug']            = true;  // prevent php_thumb from displaying any information about your system. If true, php_thumbDebug and error messages will be disabled
$php_thumb_CONFIG['allow_src_above_docroot']  = false;  // if true, allow src to be anywhere in filesystem; if false (default) only allow src within document_root
$php_thumb_CONFIG['allow_src_above_php_thumb'] = true;   // if true (default), allow src to be anywhere in filesystem; if false only allow src within sub-directory of php_thumb installation
$php_thumb_CONFIG['allow_parameter_file']     = false;  // if true, allow use of 'file' parameter; if false (default) the 'file' parameter is disabled/ignored
$php_thumb_CONFIG['allow_parameter_goto']     = false;  // if true, allow use of 'goto' parameter; if false (default) the 'goto' parameter is disabled/ignored


// * HTTP UserAgent configuration
//$php_thumb_CONFIG['http_user_agent'] = '';                                                                                      // PHP default: none
//$php_thumb_CONFIG['http_user_agent'] = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';                                    // Windows XP, Internet Explorer
$php_thumb_CONFIG['http_user_agent'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7'; // Windows XP, Firefox


// * Compatability settings
$php_thumb_CONFIG['disable_pathinfo_parsing']        = false;  // if true, $_SERVER[PATH_INFO] is not parsed. May be needed on some server configurations to allow normal behavior.
$php_thumb_CONFIG['disable_imagecopyresampled']      = false;  // if true, ImageCopyResampled is replaced with ImageCopyResampleBicubic. May be needed for buggy versions of PHP-GD.
$php_thumb_CONFIG['disable_onlycreateable_passthru'] = true;   // if true, any image that can be parsed by GetImageSize() can be passed through; if false, only images that can be converted to GD by ImageCreateFrom(JPEG|GIF|PNG) functions are allowed


// * HTTP remote file opening settings
$php_thumb_CONFIG['http_fopen_timeout']              = 10;   // timeout (in seconds) for fopen / curl / fsockopen
$php_thumb_CONFIG['http_follow_redirect']            = true; // if true (default), follow "302 Found" redirects to new URL; if false, return error message


// * Speed optimizations configuration
$php_thumb_CONFIG['use_exif_thumbnail_for_speed'] = false; // If true, and EXIF thumbnail is available, and is larger or equal to output image dimensions, use EXIF thumbnail rather than actual source image for generating thumbnail. Benefit is only speed, avoiding resizing large image.
$php_thumb_CONFIG['allow_local_http_src']         = false; // If true, 'src' parameter can be "http://<thishostname>/path/image.ext" instead of just "/path/image.ext"; if false then display warning message to encourage more efficient local-filename calling.

// END USER CONFIGURATION SECTION

///////////////////////////////////////////////////////////////////////////////

// START DEFAULT PARAMETERS SECTION
// If any parameters are constant across ALL images, you can set them here

$php_thumb_DEFAULTS_GETSTRINGOVERRIDE = true;  // if true, any parameters in the URL will override the defaults set here; if false, any parameters set here cannot be overridden in the URL
$php_thumb_DEFAULTS_DISABLEGETPARAMS  = false; // if true, GETstring parameters will be ignored (except for 'src') and only below default parameters will be used; if false, both default and GETstring parameters will be used (depending on $php_thumb_DEFAULTS_GETSTRINGOVERRIDE). Will be auto-set true if !empty($php_thumb_CONFIG['cache_default_only_suffix'])

//$php_thumb_DEFAULTS['w']    = 200;
//$php_thumb_DEFAULTS['fltr'] = array('blur|10');
//$php_thumb_DEFAULTS['q']    =  90;


// END DEFAULT PARAMETERS SECTION



///////////////////////////////////////////////////////////////////////////////
// Function for generating hashed calls to php_thumb if 'high_security_enabled'
// example:
//   require_once($_SERVER['DOCUMENT_ROOT'].'/php_thumb/php_thumb.config.php');
//   echo '<img src="'.php_thumbURL('src=/images/pic.jpg&w=50').'">';

function php_thumbURL($ParameterString) {
	global $php_thumb_CONFIG;
	return str_replace(@$php_thumb_CONFIG['document_root'], '', dirname(__FILE__)).DIRECTORY_SEPARATOR.'php_thumb.php?'.$ParameterString.'&hash='.md5($ParameterString.@$php_thumb_CONFIG['high_security_password']);
}

///////////////////////////////////////////////////////////////////////////////
/*

Безопасность
require_once('php_thumb.config.php');
    echo '<img src="'.php_thumbURL('src=pic.jpg&w=50').'">';
///////////////////////////////////////////////////////////


===========
Parameters:
===========

 src = filename of source image
 new = create new image, not thumbnail of existing image.
       Requires "w" and "h" parameters set.
       [ex: &new=FF0000|75] - red background, 75% opacity
       Set to hex color string of background. Opacity is
       optional (defaults to 100% opaque).
   w = max width of output thumbnail in pixels
   h = max height of output thumbnail in pixels
  wp = max width for portrait images
  hp = max height for portrait images
  wl = max width for landscape images
  hl = max height for landscape images
  ws = max width for square images
  hs = max height for square images
   f = output image format ("jpeg", "png", or "gif")
   q = JPEG compression (1=worst, 95=best, 75=default)
  sx = left side of source rectangle (default = 0)
       (values 0 < sx < 1 represent percentage)
  sy = top side of source rectangle (default = 0)
       (values 0 < sy < 1 represent percentage)
  sw = width of source rectangle (default = fullwidth)
       (values 0 < sw < 1 represent percentage)
  sh = height of source rectangle (default = fullheight)
       (values 0 < sh < 1 represent percentage)
  zc = zoom-crop. Will auto-crop off the larger dimension
       so that the image will fill the smaller dimension
       (requires both "w" and "h", overrides "iar", "far")
       Set to "1" or "C" to zoom-crop towards the center,
       or set to "T", "B", "L", "R", "TL", "TR", "BL", "BR"
       to gravitate towards top/left/bottom/right directions
       (requies ImageMagick for values other than "C" or "1")
  bg = background hex color (default = FFFFFF)
  bc = border hex color (default = 000000)
fltr = filter system. Call as an array as follows:
       - "brit" (Brightness) [ex: &fltr[]=brit|<value>]
         where <value> is the amount +/- to adjust brightness
         (range -255 to 255)
         Availble in PHP5 with bundled GD only.
       - "cont" (Constrast) [ex: &fltr[]=cont|<value>]
         where <value> is the amount +/- to adjust contrast
         (range -255 to 255)
         Availble in PHP5 with bundled GD only.
       - "gam" (Gamma Correction) [ex: &fltr[]=gam|<value>]
         where <value> can be a number 0.01 to 10 (default 1.0)
         Must be >0 (zero gives no effect). There is no max,
         although beyond 10 is pretty useless. Negative
         numbers actually do something, maybe not quite the
         desired effect, but interesting nonetheless.
       - "sat" (SATuration) [ex: &fltr[]=sat|<value>]
         where <value> is a number between zero (no change)
         and -100 (complete desaturation = grayscale), or it
         can be any positive number for increased saturation.
       - "ds" (DeSaturate) [ex: &fltr[]=ds|<value>]
         is an alias for "sat" except values are inverted
         (positive values remove color, negative values boost
         saturation)
       - "gray" (Grayscale) [ex: &fltr[]=gray]
         remove all color from image, make it grayscale
       - "th" (Threshold) [ex: &fltr[]=th|<value>]
         makes image greyscale, then sets all pixels brighter
         than <value> (range 0-255) to white, and all pixels
         darker than <value> to black
       - "rcd" (Reduce Color Depth) [ex: &fltr[]=rcd|<c>|<d>]
         where <c> is the number of colors (2-256) you want
         in the output image, and <d> is "1" for dithering
         (deault) or "0" for no dithering
       - "clr" (Colorize) [ex: &fltr[]=clr|<value>|<color>]
         where <value> is a number between 0 and 100 for the
         amount of colorization, and <color> is the hex color
         to colorize to.
       - "sep" (Sepia) [ex: &fltr[]=sep|<value>|<color>]
         where <value> is a number between 0 and 100 for the
         amount of colorization (default=50), and <color> is
         the hex color to colorize to (default=A28065).
         Note: this behaves differently when applied by
         ImageMagick, in which case 80 is default, and lower
         values give brighter/yellower images and higher
         values give darker/bluer images
       - "usm" (UnSharpMask) [ex: &fltr[]=usm|<a>|<r>|<t>]
         where <a> is the amount (default = 80, range 0-255),
         <r> is the radius (default = 0.5, range 0.0-10.0),
         <t> is the threshold (default = 3, range 0-50).
       - "blur" (Blur) [ex: &fltr[]=blur|<radius>]
         where (0 < <radius> < 25) (default = 1)
       - "gblr" (Gaussian Blur) [ex: &fltr[]=gblr]
         Availble in PHP5 with bundled GD only.
       - "sblr" (Selective Blur) [ex: &fltr[]=gblr]
         Availble in PHP5 with bundled GD only.
       - "smth" (Smooth) [ex: &fltr[]=smth|<value>]
         where <value> is the weighting value for the matrix
         (range -10 to 10, default 6)
         Availble in PHP5 with bundled GD only.
       - "lvl" (Levels)
         [ex: &fltr[]=lvl|<channel>|<method>|<threshold>
         where <channel> can be one of 'r', 'g', 'b', 'a' (for
         Red, Green, Blue, Alpha respectively), or '*' for all
         RGB channels (default) based on grayscale average.
         ImageMagick methods can support multiple channels
         (eg "lvl|rg|3") but internal methods cannot (they will
         use first character of channel string as channel)
         <method> can be one of:
         0=Internal RGB;
         1=Internal Grayscale;
         2=ImageMagick Contrast-Stretch (default)
         3=ImageMagick Normalize (may appear over-saturated)
         <threshold> is how much of brightest/darkest pixels
         will be clipped in percent (default = 0.1%)
         Using default parameters (&fltr[]=lvl) is similar to
         Auto Contrast in Adobe Photoshop.
       - "wb" (White Balance) [ex: &fltr[]=wb|<c>]
         where <c> is the target hex color to white balance
         on, this color is what "should be" white, or light
         gray. The filter attempts to maintain brightness so
         any gray color can theoretically be used. If <c> is
         omitted the filter guesses based on brightest pixels
         in each of RGB
         OR <c> can be the percent of white clipping used
         to calculate auto-white-balance (default = 0.1%)
         NOTE: "wb" in default settings already gives an effect
         similar to "lvl", there is usually no need to use "lvl"
         if "wb" is already used.
       - "hist" (Histogram)
         [ex: &fltr[]=hist|<b>|<c>|<w>|<h>|<a>|<o>|<x>|<y>]
         Where <b> is the color band(s) to display, from back
         to front (one or more of "rgba*" for Red Green Blue
         Alpha and Grayscale respectively);
         <c> is a semicolon-seperated list of hex colors to
         use for each graph band (defaults to FF0000, 00FF00,
         0000FF, 999999, FFFFFF respectively);
         <w> and <h> are the width and height of the overlaid
         histogram in pixels, or if <= 1 then percentage of
         source image width/height;
         <a> is the alignment (same as for "wmi" and "wmt");
         <o> is opacity from 0 (transparent) to 100 (opaque)
             (requires PHP v4.3.2, otherwise 100% opaque);
         <x> and <y> are the edge margin in pixels (or percent
             if 0 < (x|y) < 1)
       - "over" (OVERlay/underlay image) overlays an image on
         the thumbnail, or overlays the thumbnail on another
         image (to create a picture frame for example)
         [ex: &fltr[]=over|<i>|<u>|<m>|<o>]
         where <i> is the image filename; <u> is "0" (default)
         for overlay the image on top of the thumbnail or "1"
         for overlay the thumbnail on top of the image; <m> is
         the margin - can be absolute pixels, or if < 1 is a
         percentage of the thumbnail size [must be < 0.5]
         (default is 0 for overlay and 10% for underlay);
         <o> is opacity (0 = transparent, 100 = opaque)
             (requires PHP v4.3.2, otherwise 100% opaque);
         (thanks raynerapeШgmail*com, shabazz3Шmsu*edu)
       - "wmi" (WaterMarkImage)
         [ex: &fltr[]=wmi|<f>|<a>|<o>|<x>|<y>|<r>] where
         <f> is the filename of the image to overlay;
         <a> is the alignment (one of BR, BL, TR, TL, C,
             R, L, T, B, *) where B=bottom, T=top, L=left,
             R=right, C=centre, *=tile)
             *or*
             an absolute position in pixels (from top-left
             corner of canvas to top-left corner of overlay)
             in format {xoffset}x{yoffset} (eg: "10x20")
             note: this is center position of image if <x>
             and <y> are set
         <o> is opacity from 0 (transparent) to 100 (opaque)
             (requires PHP v4.3.2, otherwise 100% opaque);
         <x> and <y> are the edge (and inter-tile) margin in
             pixels (or percent if 0 < (x|y) < 1)
             *or*
             if <a> is absolute-position format then <x> and
             <y> represent maximum width and height that the
             watermark image will be scaled to fit inside
         <r> is rotation angle of overlaid watermark
       - "wmt" (WaterMarkText)
         [ex: &fltr[]=wmt|<t>|<s>|<a>|<c>|<f>|<o>|<m>|<n>|<b>|<O>|<x>]
         where:
         <t> is the text to use as a watermark;
             URLencoded Unicode HTMLentities must be used for
               characters beyond chr(127). For example, the
               "eighth note" character (U+266A) is represented
               as "&#9834;" and then urlencoded to "%26%239834%3B"
             Any instance of metacharacters will be replaced
             with their calculated value. Currently supported:
               ^Fb = source image filesize in bytes
               ^Fk = source image filesize in kilobytes
               ^Fm = source image filesize in megabytes
               ^X  = source image width in pixels
               ^Y  = source image height in pixels
               ^x  = thumbnail width in pixels
               ^y  = thumbnail height in pixels
               ^^  = the character ^
         <s> is the font size (1-5 for built-in font, or point
             size for TrueType fonts);
         <a> is the alignment (one of BR, BL, TR, TL, C, R, L,
             T, B, * where B=bottom, T=top, L=left, R=right,
             C=centre, *=tile);
             note: * does not work for built-in font "wmt"
             *or*
             an absolute position in pixels (from top-left
             corner of canvas to top-left corner of overlay)
             in format {xoffset}x{yoffset} (eg: "10x20")
         <c> is the hex color of the text;
         <f> is the filename of the TTF file (optional, if
             omitted a built-in font will be used);
         <o> is opacity from 0 (transparent) to 100 (opaque)
             (requires PHP v4.3.2, otherwise 100% opaque);
         <m> is the edge (and inter-tile) margin in percent;
         <n> is the angle
         <b> is the hex color of the background;
         <O> is background opacity from 0 (transparent) to
             100 (opaque)
             (requires PHP v4.3.2, otherwise 100% opaque);
         <x> is the direction(s) in which the background is
             extended (either 'x' or 'y' (or both, but both
             will obscure entire image))
             Note: works with TTF fonts only, not built-in
       - "flip" [ex: &fltr[]=flip|x   or   &fltr[]=flip|y]
         flip image on X or Y axis
       - "ric" [ex: &fltr[]=ric|<x>|<y>]
         rounds off the corners of the image (to transparent
         for PNG output), where <x> is the horizontal radius
         of the curve and <y> is the vertical radius
       - "elip" [ex: &fltr[]=elip]
         similar to rounded corners but more extreme
       - "mask" [ex: &fltr[]=mask|filename.png]
         greyscale values of mask are applied as the alpha
         channel to the main image. White is opaque, black
         is transparent.
       - "bvl" (BeVeL) [ex: &fltr[]=bvl|<w>|<c1>|<c2>]
         where <w> is the bevel width, <c1> is the hex color
         for the top and left shading, <c2> is the hex color
         for the bottom and right shading
       - "bord" (BORDer) [ex: &fltr[]=bord|<w>|<rx>|<ry>|<c>
         where <w> is the width in pixels, <rx> and <ry> are
         horizontal and vertical radii for rounded corners,
         and <c> is the hex color of the border
       - "fram" (FRAMe) draws a frame, similar to "bord" but
         more configurable
         [ex: &fltr[]=fram|<w1>|<w2>|<c1>|<c2>|<c3>]
         where <w1> is the width of the main border, <w2> is
         the width of each side of the bevel part, <c1> is the
         hex color of the main border, <c2> is the highlight
         bevel color, <c3> is the shadow bevel color
       - "drop" (DROP shadow)
         [ex: &fltr[]=drop|<d>|<w>|<clr>|<a>|<o>]
         where <d> is distance from image to shadow, <w> is
         width of shadow fade (not yet implemented), <clr> is
         the hex color of the shadow, <a> is the angle of the
         shadow (default=225), <o> is opacity (0=transparent,
         100=opaque, default=100) (not yet implemented)
       - "crop" (CROP image)
         [ex: &fltr[]=crop|<l>|<r>|<t>|<b>]
         where <l> is the number of pixels to crop from the left
         side of the resized image; <r>, <t>, <b> are for right,
         top and bottom respectively. Where (0 < x < 1) the
         value will be used as a percentage of width/height.
         Left and top crops take precedence over right and
         bottom values. Cropping will be limited such that at
         least 1 pixel of width and height always remains.
       - "rot" (ROTate)
         [ex: &fltr[]=rot|<a>|<b>]
         where <a> is the rotation angle in degrees; <b> is the
         background hex color. Similar to regular "ra" parameter
         but is applied in filter order after regular processing
         so you can rotate output of other filters.
       - "size" (reSIZE)
         [ex: &fltr[]=size|<x>|<y>|<s>]
         where <x> is the horizontal dimension in pixels, <y> is
         the vertical dimension in pixels, <s> is boolean whether
         to stretch (if 1) or resize proportionately (0, default)
         <x> and <y> will be interpreted as percentage of current
         output image size if values are (0 < X < 1)
         NOTE: do NOT use this filter unless absolutely neccesary.
         It is only provided for cases where other filters need to
         have absolute positioning based on source image and the
         resultant image should be resized after other filters are
         applied. This filter is less efficient than the standard
         resizing procedures.
       - "stc" (Source Transparent Color)
         [ex: &fltr[]=stc|<c>|<n>|<x>]
         where <c> is the hex color of the target color to be made
         transparent; <n> is the minimum threshold in percent (all
         pixels within <n>% of the target color will be 100%
         transparent, default <n>=5); <x> is the maximum threshold
         in percent (all pixels more than <x>% from the target
         color will be 100% opaque, default <x>=10); pixels between
         the two thresholds will be partially transparent.
md5s = MD5 hash of the source image -- if this parameter is
       passed with the hash of the source image then the
       source image is not checked for existance or
       modification and the cached file is used (if
       available). If 'md5s' is passed an empty string then
       php_thumb.php dies and outputs the correct MD5 hash
       value.  This parameter is the single-file equivalent
       of 'cache_source_filemtime_ignore_*' configuration
       paramters
 xto = EXIF Thumbnail Only - set to only extract EXIF
       thumbnail and not do any additional processing
  ra = Rotate by Angle: angle of rotation in degrees
       positive = counterclockwise, negative = clockwise
  ar = Auto Rotate: set to "x" to use EXIF orientation
       stored by camera. Can also be set to "l" or "L"
       for landscape, or "p" or "P" for portrait. "l"
       and "P" rotate the image clockwise, "L" and "p"
       rotate the image counter-clockwise.
 sfn = Source Frame Number - use this frame/page number for
       multi-frame/multi-page source images (GIF, TIFF, etc)
 aoe = Output Allow Enlarging - override the setting for
       $CONFIG['output_allow_enlarging'] (1=on, 0=off)
       ("far" and "iar" both override this and allow output
       larger than input)
 iar = Ignore Aspect Ratio - disable proportional resizing
       and stretch image to fit "h" & "w" (which must both
       be set).  (1=on, 0=off)  (overrides "far")
 far = Force Aspect Ratio - image will be created at size
       specified by "w" and "h" (which must both be set).
       Alignment: L=left,R=right,T=top,B=bottom,C=center
       BL,BR,TL,TR use the appropriate direction if the
       image is landscape or portrait.
 dpi = Dots Per Inch - input DPI setting when importing from
       vector image format such as PDF, WMF, etc
 sia = Save Image As - default filename to save generated
       image as. Specify the base filename, the extension
       (eg: ".png") will be automatically added
maxb = MAXimum Byte size - output quality is auto-set to
       fit thumbnail into "maxb" bytes  (compression
       quality is adjusted for JPEG, bit depth is adjusted
       for PNG and GIF)
down = filename to save image to. If this is set the
       browser will prompt to save to this filename rather
       than display the image

// Deprecated:
file = if set then thumbnail will be rendered to this
       filename, not output and not cached.
       (Deprecated. Disabled by default since v1.6.0,
       unavailable in v1.7.5 and later. You should
       instantiate your own object instead)
goto = URL to redirect to after rendering image to file
       * Must begin with "http://"
       * Requires file parameter set
       (Deprecated. Disabled by default since v1.6.0,
       unavailable in v1.7.5 and later. You should
       instantiate your own object instead)
 err = custom error image filename instead of showing
       error messages (for use on production sites)
       (Deprecated. Disabled by default since v1.6.0,
       unavailable in v1.7.5 and later. You should
       instantiate your own object instead)

*/
?>