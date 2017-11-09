<?php
/*	$_FILE['file_name'] e' un array associativo con 5 campi: 'name', 'type', 'size', 'tmp_name', 'error'	*/
require_once(LIB_PATH.DS."initialize.php");

class Image {
	public static $table_name = "images";
	private static $target_path = SITE_ROOT.DS.'public'.DS.'images';

	public $id;
	public $filename;
	public $type;
		
	
	public static function find_all(){
		return self::find_by_sql("SELECT * FROM " . self::$table_name);
	}

	
	public static function find_by_id($id=0) {
		global $database;
		$result_object_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE id=".$database->escape_value($id)." LIMIT 1");
		return !empty($result_object_array) ? array_shift($result_object_array) : false;
	}
	
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}

	private function has_attribute($attribute) {
		$object_vars = get_object_vars($this);
		return array_key_exists($attribute, $object_vars);
	}
	
	private static function instantiate($record) {
		$object = new self;
		foreach($record as $attribute=>$value){
			if ($object->has_attribute($attribute))
			$object->$attribute = $value;
		}
		return $object;
	}

	public static function save($file) {
		global $database;  
		$id = generateRandomUniqueString(20);
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION); //ricava estensione file
		$sql  = "INSERT INTO ".self::$table_name." (";
		$sql .= "filename, type";
		$sql .= ") VALUES ('";
		$sql .= $database->escape_value($id.'.'.$ext). "', '";
		$sql .= $database->escape_value($ext). "')";
		if ($database->query($sql)) { //true or false
			if (move_uploaded_file($file['tmp_name'], self::$target_path. DS . $id.'.'.$ext)) { 
				return $id;
			}
		} 
		return false;
	}
	
	public static function destroy($id) {
		global $database;
		$sql  = "DELETE FROM file ";
		$sql .= "WHERE id='" . $database->escape_value($id) . "'";
		$sql .= " LIMIT 1";
		$database->query($sql);
		if ($database->affected_rows() == 1) {
			return unlink(self::$target_path. DS . self::filename_by_id($id)) ? true : false; 
		} else { 
			return false; 
		}
	}

}
