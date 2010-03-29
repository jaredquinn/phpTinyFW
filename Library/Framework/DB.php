<?php

class Framework_DB {

	protected $db;

	protected $pdo;

	protected $_username;

	protected $_password;

	protected $_connStr;

	public static function getInstance() {
	  global $framework_db;
	  if(!$framework_db) { $framework_db = new Framework_DB(); }
	  return $framework_db;
	}

	public function setConnectionString($connectionString = null) {
	  if($connectionString) { $this->_connStr = $connectionString; }
	  return $this->_connStr;
	}

	public function getConnectionString() {
	  return $this->setConnectionString();
	}

	public function setUsername($username = null) {
	  if($username) { $this->_username = $username; }
  	  return $this->_username;
	}

	public function getUsername() {
	  return $this->setUsername();
	}

	public function setPassword($password = null) {
	  if($password) { $this->_password = $password; }
	  return $this->_password;
	}

	public function getPassword() {
	  return $this->setPassword();
	}

	public function connectDb() {
	  $this->pdo = new PDO($this->getConnectionString(), $this->getUsername(), $this->getPassword(),
		array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
	  );
  	  $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}

	public function getDataConnection() {
	  if(!$this->pdo) {
	    $this->connectDb();
	  }
	  return $this->pdo;
	}

}
