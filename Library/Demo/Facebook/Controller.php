<?php
class Demo_Facebook_Controller extends Framework_Controller {
	
	public function __construct() {
		parent::__construct();
		Framework_View::getInstance()->addStyleSheet('/css/demo/facebook.css');
		Framework_View::getInstance()->addScript('/js/jquery-1.3.2.min.js');
	}

	public function slideshowAction() {
		// no controller logic
	}

	public function friendsAction() {
		// no controller logic
	}

	public function timezoneAction() {
		// no controller logic
	}

}

