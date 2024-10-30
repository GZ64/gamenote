<?php

namespace application\settings;

class Settings {
	private static $instance;
	
	private function __construct() {
		define('DS', DIRECTORY_SEPARATOR);
		define('APP_ROOT', str_replace('public/index.php', 'application' . DS, $_SERVER['SCRIPT_FILENAME']));
		define('LIB_ROOT', str_replace('public/index.php', 'library' . DS, $_SERVER['SCRIPT_FILENAME']));
		define('LINK_WEB', str_replace('public/index.php', '', 'http://localhost' . $_SERVER['SCRIPT_NAME']));
		define('DATA_WEB', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
		
		define('DB_HOST', "localhost");
		define('DB_NAME', "gamenote");
		define('DB_USER', "root");
		define('DB_PASS', "greg");
		define('DB_CHAR', "utf-8");
		define("SALT", 'a"z(erètçy)u-ilgfd=::/kjk;;ui:rfghe^;ezzr$;');
	}
	
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}