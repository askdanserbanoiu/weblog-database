<?php require_once(LIB_PATH.DS."image.php");

class Article {
	/*CAMPI DATI RELATIVI A UNA ARTICOLO*/
	private static $table_name ="articles";
	public $title;
	public $article_img_id;
	public $body;
	public $author;
	public $created;
	public $subjects; //array di soggetti
	
	/*COSTRUTTORI*/
	
	public function __construct($title="", $article_img_id=0, $body="", $author="", $created="", $subjects=NULL) {
		$this->title = $title;
		$this->article_img_id = (int)$article_img_id;
		$this->body = $body;
		$this->author = $author;
		$this->created = $created;
		
		$i = 0;
		while ($i < count($subjects)){
			 $this->subjects[$i] = $subjects[$i];
			 $i++;
		}
	}
	
	/*SETTERS*/
	
	public function set_body($body=null) {
		if ($body != null) $this->body = $body;
	}
	
	public function set_article_img_id($id) {
		if ($id != null) $this->article_img_id = $id;
	}
	
	/*GETTERS*/
	
	public function get_article_title() {
		return $this->title;
	}
	
	public function get_article_body() {
		return $this->body;
	}
	
	public function get_article_img_id(){
		return $this->article_img_id;
	}
	
	public function get_all_subjects() {
		global $database;
		$sql = "SELECT B.subject from belonging as B WHERE B.article_id = '{$this->title}'";
		return mysqli_fetch_array($database->query($sql)); 
		/*while ($i < count($subjects)) {
			$html .= "{$subjects[$i]} ";
			$i++;
		}
		return $html;*/
	}
	
	public static function find_all(){
		return self::find_by_sql("SELECT * FROM " . self::$table_name);
	}
	/*POST = (RITORNA UN ARRAY DI OGGETTI Photograph T.C L'ARRAY CONTIENE TUTTI GLI UTENTI DELLA TABELLA photographs NEL DB photo_gallery)*/
	

	public static function find_by_id($id=0) {
		global $database;
		$result_object_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE title='".$database->escape_value($id)."' LIMIT 1");
		return !empty($result_object_array) ? array_shift($result_object_array) : false;
	}
	/*POST = (RITORNA UN OGGETTO Photograph T.C. Photograh->id = $id DALLA TABELLA photographs NEL DB photo_gallery)*/
	
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}
	/*POST = (RITORNA UN ARRAY DI OGGETTI Photograph, RISULTATO DELLA QUERY $sql SULLA TABELLA photographs DI photo_gallery)

	/*returns true if ...,else false*/
	private function has_attribute($attribute) {
		$object_vars = get_object_vars($this);
		return array_key_exists($attribute, $object_vars);
	}
	
	/*returns an User dobject with all its attributes istantiated with the corresponding counterparts from $record*/
	private static function instantiate($record) {
		$object = new self;
		foreach($record as $attribute=>$value){
			if ($object->has_attribute($attribute))
			$object->$attribute = $value;
		}
		return $object;
	}
	
	public function save() {
		return isset($this->id) ? $this->update() : $this->create();
	}


	
	public function create(){
		global $database;  
		$sql  = "INSERT INTO ".self::$table_name." (";
		$sql .= "title, article_img_id, body, author, created";
		$sql .= ") VALUES ('";
		$sql .= $database->escape_value($this->title). "', ";
		$sql .= $this->article_img_id. ", '";
		$sql .= $database->escape_value($this->body). "', '";
		$sql .= $database->escape_value($this->author). "', '";
		$sql .= $database->escape_value($this->created). "') ";
		if ($database->query($sql)) {
			$sql = "INSERT INTO belonging ";
			$sql .= "(article_id, subject)";
			$sql .= " VALUES ";
			for ($i=0; $i < count($this->subjects); $i++) {
				$subject = $this->subjects[$i];
				if ($i == (count($this->subjects) - 1)) $sql .= "('{$this->title}', '{$subject}'); ";
				else $sql .= "('{$this->title}', '{$subject}'), ";
			}
			if ($database->query($sql)) { //true or false
				return true;
			} else {
				return false;
			}
		}
		else return false;
	}
	/*POST = (INSERISCE LE INFORMAZIONI DEL OGGETTO PHOTOGRAPH DI UN CORRISPONDENTE FILE IMMAGINE NEL DB photographs DI 
	  photo_gallery E RITORNA TRUE, FALSE SE INSERIMENTO FALLISCE)*/
	
	public function update(){
		global $database;
		$sql  = "UPDATE ".self::$table_name." SET ";
		$sql .= "article_img_id=".$this->article_img_id. ", ";
		$sql .= "body='".$database->escape_value($this->body). "' ";
		$sql .= "WHERE title='".$this->title."'";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false; /*PERCHE QUANDO FACCIO UPDATE NON HO TRUE O FALSE*/
	}
	/*POST = (AGGIORNA LE INFORMAZIONI DEL OGGETTO PHOTOGRAPH DI UN CORRISPONDENTE FILE IMMAGINE NEL DB photographs 
	  DI photo_gallery e RITORNA TRUE, FALSE SE AGGIORNAMENTO FALLISCE)*/
	
	
	public function delete(){
		global $database;
		$sql  = "DELETE FROM ".self::$table_name . " ";
		$sql .= "WHERE id=" . $database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	
	public static function count_by($author=null, $subject=null, $month=null, $year=null) {
		global $database;
		$sql = "select count(DISTINCT A.title) from articles as A, belonging as B WHERE A.title = B.article_id ";
		if ($author != null) $sql .= "AND author='{$author}' ";
		if ($subject != null) $sql .= "AND B.subject='{$subject}' ";
		if ($month != null) $sql .= "AND month(A.created) = {$month} ";
		if ($year != null) $sql .= "AND year(A.created) = {$year} ";
		$sql .= "ORDER BY CREATED ASC ";
		$result_set = $database->query($sql); //returns it as a row anyway
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	
	public function view_article($with_link=true) {
		$img = Image::find_by_id($this->article_img_id);
		if ($img) $path = $img->get_img_path(); else $path = false;
		
		$html= "<div class=\"article\">";
		if ($with_link) $html .= "<a href=\"article.php?article_id={$this->title}\" ><h1>{$this->title}</h1></a>";
		else $html .= "<h1>{$this->title}</h1>";
		if ($path) $html .= "<div class=\"article_img\"><img src=\"{$path}\" /><span class=\"helper\"></span></div>";
		$html .= "<div class=\"article_body\"><p>{$this->body}</p></div>";
	    $html .= "<p>{$this->created}</p>";
	    $html .= "</div>";
		return $html;
	}
	
	public function view_article_user() {
		$html  = "<tr>";
		$html .= "<td>{$this->title}</td>";
		$html .= "<td>{$this->author}</td>";
		$html .= "<td>{$this->created}</td>";
		//$html .= "<td>{$this->subject}</td>";
		$html .= "<td><a href=\"article.php?article_id={$this->title}\">View</a></td>";
		$html .= "<td><a href=\"control/edit_article.php?article={$this->title}\">Edit</a></td>";
		$html .= "<td><a href=\"control/delete_article.php?article={$this->title}\">Delete</a></td>";
	    $html .= "</tr>";
		return $html;
	}

	
	public static function view_current_page_articles($pagination, $author=null, $subject=null, $month=null, $year=null, $user=false) {
		
		$sql = "select distinct A.* from articles as A, belonging as B WHERE A.title = B.article_id ";
		if ($author != null) $sql .= "AND author='{$author}' ";
		if ($subject != null) $sql .= "AND B.subject='{$subject}' ";
		if ($month != null) $sql .= "AND month(A.created) = {$month} ";
		if ($year != null) $sql .= "AND year(A.created) = {$year} ";
		$sql .= "LIMIT {$pagination->per_page} ";
		$sql .= "OFFSET {$pagination->offset()}";
		return Article::find_by_sql($sql);




		/*$author_h1 = "all"; $subject_h1 = "all"; $month_h1 = "all"; $year_h1 = "all";
		if ($author != NULL) $author_h1 = $author; 
		if ($subject != NULL) $subject_h1 = $subject; 
		if ($month != NULL) $month_h1 = $month; 
		if ($year != NULL) $year_h1 = $year; 
		$html = "<h1>AUTHOR: {$author_h1}, SUBJECT: {$subject_h1}, MONTH: {$month_h1}, YEAR: {$year_h1}</h1>";
		if (!$user) {
			foreach($articles as $article): 
			   $html .= $article->view_article(); 
		    endforeach;
		}
		else {
			$html .= "<table>";
			foreach($articles as $article): 
				$html .= $article->view_article_user();	    
			endforeach;
			$html .= "</table>";
		}
		return $html;*/
	}//$user bool: 1 sono nella sezione utenti, 0 sono nella pagina pubblica
	
	public static function most_commented($month, $year, $user=false) {
		global $database;
		$sql  = "select A.*, count(C.id) AS commenti_tot from articles as A, comments as C ";
        $sql .= "where A.title = C.article_id and month(C.created) = '{$month}' and year(C.created) = '{$year}' ";
        $sql .= "group by A.title ORDER BY commenti_tot DESC LIMIT 5 ";
		return $database->query($sql);
		/* "<h1>month: {$month}, year: {$year}</h1>";

		if (!$user) {
			foreach($articles as $article): 
			   $html .= $article->view_article(); 
		    endforeach;
		}
		else {
			$html .= "<table>";
			foreach($articles as $article): 
				$html .= $article->view_article_user();	    
			endforeach;
			$html .= "</table>";
		}
		return $html;*/
	}
	
	public static function get_oldest_year() {
		global $database;
		$sql = "SELECT min(year(A.created)) as anno_min FROM articles as A";
		$oldest = $database->fetch_array($database->query($sql));
		return $oldest[0];
	}
}
	  
?>
	