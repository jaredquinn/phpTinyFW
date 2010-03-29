<?php

class Framework_Table_Row {

	public $id;

	public function __construct($array = null) {
  	  if(is_array($array)) {		
	    $this->populate($array);
	  }
	}

	public function populate($array) {
		foreach($array as $k=>$v) {
		  if($k == 'private') { next; }
		  if(property_exists($this, $k)) { $this->{$k} = $v; }
		}
	}

	public function preSave() {
	}

	public function toArray() {
	  return (array) $this;
	}

}

