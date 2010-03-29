<?php
/* TinyFramework
   (C) 2010 Jared Quinn
   All Rights Reserved

   Controller Base Object
*/
class Framework_Controller {

	protected $view;

	public function __construct() {
	  	$this->view = new Framework_View();
	}

	public function __fallbackAction($action = null) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
		header('Status: 404 Not Found');
		die('404 Not Found');
	}

	public function getView() {
		return Framework_View::getInstance();
	}

}

