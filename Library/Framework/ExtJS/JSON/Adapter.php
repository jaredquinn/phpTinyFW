<?
class Framework_ExtJS_JSON_Adapter {

	protected $root;

	protected $table;

	public function __construct($root) {
	   $this->root = $root;
	}

	public function setTable($table) {
	   $this->table = $table;
	}

	public function process() {
	   $xaction = $_REQUEST['xaction'];
	   $rootObject = stripslashes($_REQUEST[$this->root]);
	   $rootObject = json_decode($rootObject);
	   if(!is_array($rootObject)) { $rootObject = array($rootObject); }
	   $count = 0;
	   $res = array();
	
	   if($xaction == 'read') {
	  	$res = $this->table->findAll(	$_REQUEST['start'], 
						$_REQUEST['limit'], 
						$_REQUEST['sort'], 
						$_REQUEST['dir']    );
		$count = $this->table->count();
   	   }
	    if($xaction == 'create') {
		    foreach($rootObject as $u) {
		      $xp = $this->table->create((array) $u);
		      $xp = $this->table->save($xp);
		      $res[] = (array) $xp;
		      $count++;
		    }
	    }
	    if($xaction == 'update') {
		    foreach($rootObject as $u) {
		      $xp = $this->table->updateById($u->id, $u);
		      $xp = $this->table->save($xp);
		      $res[] = (array) $xp;
		      $count++;
		    }
	    }
	    return array($this->root => $res, 'success' => true, 'total' => $count);
	}
}
