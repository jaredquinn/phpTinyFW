<?php

class Framework {

	public static function autoload($class_name) {

	    $file = preg_replace('/_/', '/', $class_name) . '.php';

	    if(file_exists(APP_BASE . '/Library/' . $file)) {
	    	require_once $file;
	    } else {
		debug_print_backtrace();
		die();
	    }
	}

}

