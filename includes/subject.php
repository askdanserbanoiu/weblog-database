<?php require_once(LIB_PATH.DS.'database.php'); ?>
<?php
class Subject {
	/*CAMPI DATI RELATIVI A UN UTENTE*/
	private static $table_name="subjects";
	public $id;
	public $subject;
	public $description;
	
	public function __construct($subject="", $description="") {
		$this->subject = $subject;
		$this->description = $description;
	}
	
	public function set_description($description) {
		$this->description = $description;
	}

	
	public static function find_all(){
		return self::find_by_sql("SELECT * FROM " . self::$table_name);
	}
	/*POST = (RITORNA UN ARRAY DI OGGETTI UTENTE T.C L'ARRAY CONTIENE TUTTI GLI UTENTI DELLA TABELLA users NEL DB photo_gallery)*/
	
	public static function find_by_id($id="") {
		global $database;
		$result_object_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE subject='{$id}' LIMIT 1");
		return !empty($result_object_array) ? array_shift($result_object_array) : false;
	}
	/*POST = (RITORNA UN OGGETTO User T.C. User->id = $id DALLA TABELLA users NEL DB photo_gallery)*/
	

	
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}
	/*POST = (RITORNA UN ARRAY DI OGGETTI User, RISULTATO DELLA QUERY $sql SULLA TABELLA User DI photo_gallery)

	/*returns true if ...,else false*/
	private function has_attribute($attribute) {
		$object_vars = get_object_vars($this);
		return array_key_exists($attribute, $object_vars);
	}
	
	/*returns an User object with all its attributes istantiated with the corresponding counterparts from $record*/
	private static function instantiate($record) {
		$object = new self;
		foreach($record as $attribute=>$value){
			if ($object->has_attribute($attribute))
			$object->$attribute = $value;
		}
		return $object;
	}
	
	public function create(){
		global $database;  
		$sql  = "INSERT INTO ".self::$table_name." (";
		$sql .= "subject, description";
		$sql .= ") VALUES ('";
		$sql .= $database->escape_value($this->subject). "', '";
		$sql .= $database->escape_value($this->description). "' )";
		if ($database->query($sql)) { //true or false
			$this->id = $database->insert_id(); 
			return true;
		} else {
			return false;
		}
	}
	/*POST = (INSERISCE UN UTENTE NEL DB users DI photo_gallery E RITORNA TRUE, FALSE SE INSERIMENTO FALLISCE)*/
	
	public function update(){
		global $database;
		$sql  = "UPDATE ".self::$table_name." SET ";
		$sql .= "description='".$database->escape_value($this->description). "' ";
		$sql .= " WHERE subject='".$database->escape_value($this->subject)."'";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;	//not true or false for update
	}
	
	
	public function delete(){
		global $database;
		$sql  = "SELECT deletesubject('{$this->subject}') as ok";
		$ok = $database->fetch_array($database->query($sql));
		return ($ok[0]) ? true : false;
	}
	/*POST = (CANCELLA DAL DB users DI photo_gallery LA TUPLA CORRISPONDENTE ALL OGG CORRENTE E RESTITUISCE TRUE, FALSE SE QUALCOSA VA
	  STORTO NELLA CANCELLAZIONE)*/
	/*N.B. LO CANCELLA DAL DB, MA L'ISTANZA DELL'OGGETTO SU CUI APPLICO DELETE NON VIENE CANCELLLATA. OCCUPARSI DI CIO'*/
	
	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$result_set = $database->query($sql); //returns it as a row anyway
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	public static function subjects_select_menu() {
		$subjects = Subject::find_all(); 
		$html  = "<ul>";
		foreach ($subjects as $subject) {
		 	$html .= "<li><input type=\"radio\" name=\"{$subject->subject}\" value=\"".$subject->subject."\">".$subject->subject."</option></li>";
		}
		$html .= "</ul>";
        return $html;
	}
	
	public function view_subject() {
		$html  = "<tr>";
		$html .= "<td>{$this->subject}</td>";
		$html .= "<td>{$this->description}</td>";
		$html .= "<td><a href=\"control/edit_subject.php?subject={$this->subject}\">Edit</a></td>";
		$html .= "<td><a href=\"control/delete_subject.php?subject={$this->subject}\">Delete</a></td>";
	    $html .= "</tr>";
		return $html;
	}
	
	public static function get_most_used_subject($author) {
		global $database;
		$sql  = "select Z.subject, max(quanto_usato) from (select B.subject, count(B.subject) AS quanto_usato ";
		$sql .= "from (select * from articles where author='{$author}') as A, belonging as B ";
		$sql .= "where A.title = B.article_id group by A.title) as Z ";
		$subject = $database->fetch_array($database->query($sql));
		return $subject[0];
	}
	
	public static function view_all_subjects(){
		$subjects = self::find_all();
		$html = "<table><tr id=\"thead\"><td>SUBJECT</td><td>DESCRIPTION</td></tr>";
		foreach($subjects as $subject): 
		    $html .= $subject->view_subject();
	    endforeach;
		$html .= "</table>";
		return $html;
	}


}