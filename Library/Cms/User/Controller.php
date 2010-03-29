<?php

class Cms_User_Controller extends Framework_Controller {

	protected $userTable;

	public function __construct() {
		parent::__construct();
		$this->userTable = new Cms_User_Table_User();
	}

	public function indexAction() {
		$this->getView()->addScript('/js/cms/user.js');
	}

	public function adapterAction() {
		$adapter = new Framework_ExtJS_JSON_Adapter('User');
		$adapter->setTable($this->userTable);
	        $this->getView()->jsonResult( $adapter->process() );;
	}
	
}

