<?php
if (!file_exists('php_thumb.config.php')) {
	if (file_exists('php_thumb.config.php.default')) {
		echo 'WARNING! "php_thumb.config.php.default" MUST be renamed to "php_thumb.config.php"';
	} else {
		echo 'WARNING! "php_thumb.config.php" should exist but does not';
	}
	exit;
}
header('Location: /');
?>