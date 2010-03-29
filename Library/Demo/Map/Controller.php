<?php
class Demo_Map_Controller extends Framework_Controller {

	public function indexAction() {
		$this->getView()->addStyleSheet('/css/demo/map.css');
		$this->getView()->addScript('/js/jquery-1.3.2.min.js');

		$this->getView()->addScript("http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAo0Jsw9vf_zvuRjRfTlfdmxSs9sjmR6J3n5k5GSavWSu-rzMOaRRAPXIb7zEdMbhjlPHKARg1jG3HoA");
		$this->getView()->addScript('/js/demo/map.js');
	}

	public function feedAction() {
		header('Content-type: application/xml');
		die(file_get_contents('http://earthquake.usgs.gov/earthquakes/catalogs/1day-M2.5.xml'));
	}

}

