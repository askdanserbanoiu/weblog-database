<?php
require_once(LIB_PATH.DS.'database.php');

class Comment {
	private static $table_name = "comments";	
	public $id;
	public $author;
	public $article_id;
	public $created;
	public $body;
	
	public function __construct($author="Anonymous", $article_id=0, $body="") {
		if (!empty($author) && !empty($article_id) && !empty($body)) {
			$this->author = $author;
			$this->article_id = $article_id;
			$this->created = strftime("%Y-%m-%d %H:%M:%S", time());
			$this->body = $body;
		} else {
			return false;
		} 
	}
	
	public static function find_comments_on($article_id=NULL){
		if (!$article_id == NULL) {
			global $database;
			$sql  = "SELECT * FROM ".self::$table_name;
			$sql .= " WHERE article_id='".$article_id."'";
			$sql .= " ORDER BY created ASC";
			return self::find_by_sql($sql);
		}
	}
	
	public static function find_by_id($id=0) {
		global $database;
		$result_object_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE id={$id} LIMIT 1");
		return !empty($result_object_array) ? array_shift($result_object_array) : false;
	}
	/*POST = (RITORNA UN OGGETTO User T.C. User->id = $id DALLA TABELLA users NEL DB photo_gallery)*/
	
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql); /*result_set e' un array arr['tupla_i']['attr_i']*/
		$object_array = array(); /*array indefinito di oggetti Comment*/
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
		$object = new self; /*crea un oggetto di tipo Comment*/
		foreach($record as $attribute=>$value){ 
			if ($object->has_attribute($attribute))
			$object->$attribute = $value;
		}
		return $object; /*ritorna oggetto i cui campi dati sono inizializzati a partire da un array associativo*/
	}


	public function create(){
		global $database;  
		$sql  = "INSERT INTO ".self::$table_name." (";
		$sql .= "author, article_id, created, body";
		$sql .= ") VALUES ('";
		$sql .= $database->escape_value($this->author). "', '";
		$sql .= $this->article_id. "', '";
		$sql .= $database->escape_value($this->created). "', '";
		$sql .= $database->escape_value($this->body). "')";
		if ($database->query($sql)) { //true or false
			$this->id = $database->insert_id(); 
			return true;
		} else {
			return false;
		}
	}
	/*POST = (INSERISCE UN Comment NEL DB comments DI photo_gallery E RITORNA TRUE, FALSE SE INSERIMENTO FALLISCE)*/
	
	
	public function delete(){
		global $database;
		$sql  = "DELETE FROM ".self::$table_name . " ";
		$sql .= "WHERE id=" . $database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	/*POST = (CANCELLA DAL DB comments DI photo_gallery LA TUPLA CORRISPONDENTE ALL OGG CORRENTE E RESTITUISCE TRUE, FALSE SE QUALCOSA VA
	  STORTO NELLA CANCELLAZIONE)*/
	/*N.B. LO CANCELLA DAL DB, MA L'ISTANZA DELL'OGGETTO SU CUI APPLICO DELETE NON VIENE CANCELLLATA. OCCUPARSI DI CIO'*/
	
	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$result_set = $database>query($sql); //returns it as a row anyway
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	public function view_comment(){
		$html  = "<div class=\"comment\">";
		$html .= "<p class=\"name\">{$this->author} says:</p>";
		$html .= "<p class=\"body\">{$this->body}</p>";
		$html .= "<p class=\"created\">{$this->created}</p>";
		$html .= "</div>";
		return $html;
	}
	
	public static function view_all_comments_on($article_id=0){
		$comments = self::find_comments_on($article_id);
		$html = "";
		foreach ($comments as $comment) {
			$html .= $comment->view_comment();
		}
		return $html;
	}
	
	
	public static function view_comment_form($article_id) {
		$html  = "<h1>Add a Comment</h1>";
		$html .= "<form action=\"article.php?article_id={$article_id}\" method=\"post\">";
		$html .= "<table>";
		$html .= "<tr><td>Your name:</td><td><input type=\"text\" name=\"author\"/></td></tr>";
		$html .= "<tr><td>Your comment:</td><td><textarea name=\"body\" cols=\"40\" rows=\"8\"></textarea></td></tr>";
		$html .= "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Submit Comment\"/></td></tr>";
		$html .= "</table>";
	    $html .= "</form>";
		return $html;
	}
}



?>