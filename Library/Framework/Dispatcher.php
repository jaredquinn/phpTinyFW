<?php

/* TinyFramework
   (C) 2010 Jared Quinn
   All Rights Reserved

   Framework Dispatcher
*/
class Framework_Dispatcher {

	public $system;

	public $controller;

	public $action;

	public $params;

	public $default_system;

	public $default_controller;

	public $default_action;

	public $model_path;

	protected $ready;

	public function __construct($url = null) {
		if($url) { $this->setURL($url); }
	}

	public function getInstance($url = null) {
		global $__dispatcher;
		if(!$__dispatcher) { $__dispatcher = new Framework_Dispatcher($url); }
		return $__dispatcher;
	}

	public function setURL($url) {
		list($url, $qparam) = explode('?', $url);
		$path = explode('/', $url);
		if($path[0] == '') { array_shift($path);}

	   	$this->system = ucfirst(array_shift($path));

		if(count($path) > 0) { $this->controller = ucfirst(array_shift($path)); }
		if(count($path) > 0) { $this->action = strtolower(array_shift($path)); }
		
		$this->params = $path;
	}

	public function setupDispatch($view) {
		$this->system = $this->system ? $this->system : $this->default_system;
		$this->controller = $this->controller ? $this->controller : $this->default_controller;
		$this->action = !empty($this->action) ? $this->action : $this->default_action;
		//$this->action = "{$this->action}Action";
		$this->ready = true;
	
		// set default View	
		$view->setViewPath(sprintf("%s/Library/%s/%s/View", APP_BASE, $this->system, $this->controller));
		$view->setView($this->action);
		$view->setLayoutPath(sprintf("%s/Library/%s/Layout", APP_BASE, $this->system));
		$view->setLayout('default');
	}

	
	public function dispatch() {
		if(!$this->ready) {
			$this->setupDispatch();
		}

 		$objname = "{$this->system}_{$this->controller}_Controller";
		$controllerObject = new $objname();

		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the pas

		if($this->system == 'Framework') {
			throw new Exception('Cannot Call Framework Actions Directly');
		}

		if(method_exists($controllerObject, "{$this->action}Action")) {
			// have action
			call_user_func_array(array($controllerObject, "{$this->action}Action"), $this->params);
		} else {
			// fall back
			if(method_exists($controllerObject, "__fallbackAction")) {
				array_unshift($this->params, $this->action);
				call_user_func_array(array($controllerObject, '__fallbackAction'), $this->params);
			} else {
				throw new Exception("Unknown Action {$this->system}::{$this->controller}/{$this->action}");
			}
		}

	}

}
?>
