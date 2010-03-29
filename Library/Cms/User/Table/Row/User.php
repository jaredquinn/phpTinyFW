<?php

class Cms_User_Table_Row_User extends Framework_Table_Row {

	public $id;

	public $userName;

	public $realName;

	public $password;

	public $active;

	public function setClearPassword($password) {
		$this->password = md5($password);
	}
	
	public function populate($array) {
		if(!empty($array['clear-password'])) {
			$this->setClearPassword($array['clear-password']);
			unset($array['clear-password']);
		}
		parent::populate($array);
	}


}
