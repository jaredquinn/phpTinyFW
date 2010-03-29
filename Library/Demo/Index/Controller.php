<?php
class Demo_Index_Controller extends Framework_Controller {

	public function indexAction() {

	}

	public function zipAction() {
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="jaredquinn-demo.zip"'); 
		header('Content-Transfer-Encoding: binary');

		system("zip -r - . 2> /dev/null");
		die();
	}

}

