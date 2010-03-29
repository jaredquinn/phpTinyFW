<?php

/* Bootstrapper for the TinyFramework (C) Copyright 2010, Jared Quinn */

/* my database configuration comes from outside this tree so passwords
   dont get distributed.

   in db-config.php please define
	DB_NAME
	DB_USER
	DB_PASSWORD
	DB_HOST
*/
require_once '../db-config.php';

define(APP_BASE, dirname(__FILE__));

require_once(APP_BASE . '/Library/Framework.php');
spl_autoload_register(array('Framework', 'autoload'));

$db = Framework_DB::getInstance();
$db->setConnectionString(sprintf("mysql:dbname=%s;host=%s", DB_NAME, DB_HOST));
$db->setUsername(DB_USER);
$db->setPassword(DB_PASSWORD);


$view = Framework_View::getInstance();
//set some defaults
$view->setMetaData('keywords', 'Jared Quinn, examples, demo');
$view->setMetaData('description', 'A quick collections of demos and examples by Jared Quinn');
$view->setMetaData('robots', 'nofollow');

$dispatch = Framework_Dispatcher::getInstance($_SERVER['REQUEST_URI']);
$dispatch->default_system = 'Demo';
$dispatch->default_controller = 'Index';
$dispatch->default_action = 'index';
$dispatch->setupDispatch($view);

$view->addStyleSheet('/css/common.css');
$view->addStyleSheet('/css/' . strtolower($dispatch->system) . '/common.css');

$dispatch->dispatch();


$view->render();

?>
