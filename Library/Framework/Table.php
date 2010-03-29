<?

class Framework_Table {

	protected $table;

	protected $rowClass;

	public function findAll($start = null , $limit = null, $sort = null, $dir = null) {
	  $q = sprintf('SELECT * FROM %s', $this->table);
	  if($sort || $dir) {
	    $q .= " ORDER BY {$sort} {$dir}";
	  }
	  if($start || $limit) {
	    $q .= " LIMIT {$start}, {$limit}";
	  }
	  return $this->fetchAll($q);
	}

	public function count() {
	  $q = sprintf('SELECT COUNT(1) AS total FROM %s', $this->table);
	  $row = $this->fetchTotal($q);
	  return $row['total'];
	}


	public function findById($id) {
	  $q = sprintf('SELECT * FROM %s WHERE id = %d', $this->table, $id);
	  return $this->fetch($q);	
	}

	public function updateById($id, $data) {
	  $obj = $this->findById($id);
	  $data = (array) $data;
	  unset($data['id']);
	  $obj->populate($data);
	  return $obj;
	}

	public function save(Framework_Table_Row $record) {
	  $record->preSave();
	  $array = $record->toArray();	
	  if(array_key_exists('id', $array) && $array['id']> 0) {
		return $this->update($array);
	  } else {
		return $this->insert($array);
	  }
	}

	public function update($array) {
	  $prt = array();
	  foreach($array as $f=>$v) { if($f != 'id') { $prt[] = "`$f` = :$f"; } }
	  $sql = 'UPDATE ' . $this->table . ' SET ' . join(',', $prt) . ' WHERE id = :id';
	  $stmt = Framework_DB::getInstance()->getDataConnection()->prepare($sql);
	  $stmt->execute($array);
	  return new $this->rowClass($array);
	}


	public function insert($array) {
	  $fields = array_keys($array);
	  unset($fields['id']);
	  unset($array['id']);
	  $fld = array();
	  $val = array();
	  foreach($fields as $f) { if($f != 'id') { $fld[] = "`$f`"; $val[] = ":$f"; } }
	  $sql = 'INSERT INTO ' . $this->table . ' (' . join(',', $fld) . ') VALUES (' . join(',', $val) . ')';
	  $stmt = Framework_DB::getInstance()->getDataConnection()->prepare($sql);
	  $stmt->execute($array);
	  $array['id'] =  Framework_DB::getInstance()->getDataConnection()->lastInsertId();
	  return new $this->rowClass($array);
	}

	public function create( $array = null ) {
	  return new $this->rowClass($array);
	}
	
	public function delete($id) {
	  $sql = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
	  $stmt = Framework_DB::getInstance()->getDataConnection()->prepare($sql);
	  $stmt->execute(array('id' =>  $id));
	  return $id;
	}

	public function fetchAll($q) {
	  $db = Framework_DB::getInstance()->getDataConnection();
	  $stmt = $db->prepare($q);
	  $stmt->setFetchMode(PDO::FETCH_CLASS, $this->rowClass);
	  $stmt->execute();
	  return $stmt->fetchAll();
	}

	public function fetch($q) {
	  $db = Framework_DB::getInstance()->getDataConnection();
	  $stmt = $db->prepare($q);
	  $stmt->setFetchMode(PDO::FETCH_CLASS, $this->rowClass);
	  $stmt->execute();
	  return $stmt->fetch();
	}

	public function fetchTotal($q) {
	  $db = Framework_DB::getInstance()->getDataConnection();
	  $stmt = $db->prepare($q);
	  $stmt->setFetchMode(PDO::FETCH_ASSOC);
	  $stmt->execute();
	  return $stmt->fetch();
	}


}


