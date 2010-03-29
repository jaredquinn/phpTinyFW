<?
class Framework {

	public static function autoload($class_name) {

	    $file = APP_BASE . '/Library/' . preg_replace('/_/', '/', $class_name) . '.php';

	    if(file_exists($file)) {
	    	require_once $file;
	    } else {
		debug_print_backtrace();
		die();
	    }
	}

}

