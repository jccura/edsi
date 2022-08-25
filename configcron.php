<?php

if(isset($argv[1])) {
	if(!empty($argv[1])) {
		define('ENV', $argv[1]);
	} else {
		define('ENV', "" );
	}
} else {
	define('ENV', "" );
}

if(file_exists('./configs/env.php')){
  require_once('./configs/env.php');
} else if(file_exists('/home/iso7n2zl2kqg/public_html/'.ENV.'/configs/env.php')) {
  require_once('/home/iso7n2zl2kqg/public_html/'.ENV.'/configs/env.php');
} else {
  die('Configure environment first.');
}

// HTTP
define('HTTP_SERVER', LOCAL_PROD );
define('HTTP_IMAGE', LOCAL_PROD.'image/');
define('HTTP_ADMIN', LOCAL_PROD.'admin/');

// HTTPS
define('HTTPS_SERVER', LOCAL_PROD);
define('HTTPS_IMAGE', LOCAL_PROD.'image/');

// DIR
define('DIR_APPLICATION', DOC_ROOT.'catalog/');
define('DIR_SYSTEM', DOC_ROOT.'system/');
define('DIR_DATABASE', DOC_ROOT.'system/database/');
define('DIR_LIBRARY', DOC_ROOT.'system/library/');
define('DIR_LANGUAGE', DOC_ROOT.'catalog/language/');
define('DIR_TEMPLATE', DOC_ROOT.'catalog/view/theme/');
define('DIR_CONFIG', DOC_ROOT.'system/config/');
define('DIR_IMAGE', DOC_ROOT.'image/');
define('DIR_CACHE', DOC_ROOT.'system/cache/');
define('DIR_DOWNLOAD', DOC_ROOT.'download/');
define('DIR_LOGS', DOC_ROOT.'system/logs/');

define('MAX_NAME_COUNT', 1);
define('MAX_COUNT', 1);
define('PAIRING', 500);
define('BINARY', 200);

define('WEBSITE', 'esthetiquedirectsales');
define('IDPREFIX', 'EDSI');
define('WEBSITE_TITLE', 'Esthetique Direct Sales Inc.');
define('PAGINATION_TEXT', 'Showing {start} to {end} of {total} ({pages} Pages)');
?>