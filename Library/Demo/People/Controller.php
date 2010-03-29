<?php
class Demo_People_Controller extends Framework_Controller {

	protected $peopleTable;

	public function __construct() {
		parent::__construct();
		$this->peopleTable = new Demo_People_Table_People();
	}

	public function listAction() {
		$this->getView()->jsonResult( $this->peopleTable->findAll() );
	}

	public function deleteAction($id) {
		$this->getView()->jsonResult( $this->peopleTable->delete($id) );
	}

	public function editAction() {

		$id = (int) $_POST['id'];
		if($id > 0) {
			$person = $this->peopleTable->findById($id);
			$person->name = $_POST['name'];
			$person->description = $_POST['description'];
		} else {
			$person = $this->peopleTable->create(array(
				'name' => $_POST['name'],
				'description' => $_POST['description']
			));
		}
		$this->peopleTable->save($person);
		$this->getView()->jsonResult($person->toArray());
	}

	public function indexAction() {
		$this->getView()->addStyleSheet('/css/demo/people.css');
		$this->getView()->addStyleSheet('/css/blitzer/jquery-ui-1.7.2.custom.css');
		$this->getView()->addScript('/js/jquery-1.3.2.min.js');
		$this->getView()->addScript('/js/jquery-ui-1.7.2.custom.min.js');
		$this->getView()->addScript('/js/jquery.form.js');
		$this->getView()->addScript('/js/demo/people.js');
	}

	public function resetAction() {
		
		$existing = $this->peopleTable->findAll();
		foreach($existing as $record) {
			$this->peopleTable->delete( $record->id );
		}	

		$data = array(
			array('name' => 'Jared Quinn', 'description' => 'Superstar'),
			array('name' => 'Jean-Luc Picard', 'description' => 'Captain of the Enterprise'),
			array('name' => 'Spock', 'description' => 'is logical....'),
		);
		foreach($data as $personData) {
			$person = $this->peopleTable->create($personData);
			$this->peopleTable->save($person);
		}

		$this->getView()->jsonResult(true);
	}
		
		
}

