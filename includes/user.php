<?php require_once(LIB_PATH.DS.'database.php'); ?>
<?php
class User {
	/*CAMPI DATI RELATIVI A UN UTENTE*/
	private static $table_name="users";
	public $username;
	public $hashed_password;
	public $name;
	public $surname;
	public $birth;
	public $address;
	public $user_img_id;
	public $permission;
	
	public function get_hashed_password() {
		return $this->hashed_password;
	}
	
	public function get_user_img_id(){
		return $this->user_img_id;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_surname() {
		return $this->surname;
	}
	
	public function get_fullname() {
		return $this->name. " " . $this->surname;
	}
	
	
	public function get_username() {
		return $this->username;
	}
	
	public function get_permission() {
		return $this->permission;
	}
	
	public function get_birth() {
		return $this->birth;
	}
	
	public function get_address() {
		return $this->address;
	}
	
	public function set_by($name=null, $surname=null, $birth=null, $address=null) {
		if ($name != null) $this->name = $name;
		if ($surname != null) $this->surname = $surname;
		if ($birth != null) $this->birth = $birth;
		if ($address != null) $this->address = $address;
	}
	
	public function set_user_img_id($user_img_id=null) {
		if ($user_img_id != null) $this->user_img_id = $user_img_id;
	}

	
	public function __construct($username="", $password="", $name="", $surname="", $birth="", $address="", $user_img_id=0) {
		$this->username = $username;
		$this->hashed_password = md5($password);
		$this->name = $name;
		$this->surname = $surname;
		$this->birth = $birth;
		$this->address = $address;
		$this->user_img_id = $user_img_id;
		$this->permission = "admin";
	}

	
	public static function authenticate($username="", $password="") {
		global $database;
		$username = $database->escape_value($username);
		$sql  = "SELECT * FROM ".self::$table_name . " ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "LIMIT 1";
		$result_array = self::find_by_sql($sql);
		if (!empty($result_array)){ /*controllo se mi ha trovato l'utente tc. User->username = $username*/
			$user = array_shift($result_array); /*salvo l'utente trovato in una variabile User*/
			if (md5($password) == $user->get_hashed_password()) /*controllo se $password coincide con quella dell'User*/
				return $user; /*ritorno l'User trovato*/
			else 
				return false; /*$passord non coincide*/
		}
		else {
			return false; /*$username non esiste nel db*/
		}
	}	
	/*POST = (RITORNA UN OGGETTO User sse NELLA TABELLA users NEL DB photo_gallery ESISTE UNA TUPLA T.C 
	  username = $username && password = password, RITORNA FALSE ALTRIMENTI;*/
	
	public static function find_all(){
		return self::find_by_sql("SELECT * FROM " . self::$table_name);
	}
	/*POST = (RITORNA UN ARRAY DI OGGETTI UTENTE T.C L'ARRAY CONTIENE TUTTI GLI UTENTI DELLA TABELLA users NEL DB photo_gallery)*/
	
	public static function find_by_id($id="") {
		global $database;
		$result_object_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE username='{$id}' LIMIT 1");
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

	public function save() {
		return isset($this->id) ? $this->update() : $this->create();
	}
	/*POST = (SE ESISTE UTENTE CON ID DELL' OGGETTO UTENTE CORRENTE AGGIORNA, ALTRIMENTI CREA)*/
	
	public function create(){
		global $database;
		$sql = "SELECT adduser('{$this->username}', '{$this->hashed_password}', '{$this->name}', '{$this->surname}', '{$this->birth}', '{$this->address}', {$this->user_img_id}, '{$this->permission}')";
		$ok = $database->fetch_array($database->query($sql))[0];
		return $ok;
	}
	/*POST = (INSERISCE UN UTENTE NEL DB users DI photo_gallery E RITORNA TRUE, FALSE SE INSERIMENTO FALLISCE)*/
	
	public function update(){
		global $database;
		$sql  = "UPDATE ".self::$table_name." SET ";
		$sql .= "username='".$database->escape_value($this->username). "', ";
		$sql .= "hashed_password='".$database->escape_value($this->hashed_password). "', ";
		$sql .= "name='".$database->escape_value($this->name). "', ";
		$sql .= "surname='".$database->escape_value($this->surname). "', ";
		$sql .= "birth='".$database->escape_value($this->birth). "', ";
		$sql .= "address='".$database->escape_value($this->address). "', ";
		$sql .= "user_img_id=".$this->user_img_id.", ";
		$sql .= "permission='".$database->escape_value($this->permission)."' ";
		$sql .= " WHERE username='".$database->escape_value($this->username)."'";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;	//not true or false for update
	}
	/*POST = (AGGIORNA L'UTENTE CORRISPONDENTE NEL DB users DI photo_gallery e RITORNA TRUE, FALSE SE AGGIORNAMENTO FALLISCE)*/
	
	
	public function delete(){
		global $database;
		$sql  = "DELETE FROM ".self::$table_name . " ";
		$sql .= "WHERE username='" . $database->escape_value($this->username)."'";
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	/*POST = (CANCELLA DAL DB users DI photo_gallery LA TUPLA CORRISPONDENTE ALL OGG CORRENTE E RESTITUISCE TRUE, FALSE SE QUALCOSA VA
	  STORTO NELLA CANCELLAZIONE)*/
	/*N.B. LO CANCELLA DAL DB, MA L'ISTANZA DELL'OGGETTO SU CUI APPLICO DELETE NON VIENE CANCELLLATA. OCCUPARSI DI CIO'*/
	
	public function view_user() {
		$html  = "<tr>";
		$html .= "<td>{$this->get_username()}</td>";
		$html .= "<td>{$this->get_fullname()}</td>";
		$html .= "<td>{$this->get_permission()}</td>";
		$html .= "<td>{$this->get_birth()}</td>";
		$html .= "<td>{$this->get_address()}</td>";
		$html .= "<td><a href=\"control/delete_user.php?username={$this->username}\">Delete</a></td>";
	    $html .= "</tr>";
		return $html;
	}
	
	
	public static function view_all_users() {
		$users = self::find_all();
		$html = "<table><tr id=\"thead\"><td>USERNAME</td><td>FULLNAME</td><td>PERMISSION</td><td>BIRTH</td><td>ADDRESS</td></tr>";
		foreach($users as $user):
			if ($user->get_permission() != "root") $html .= $user->view_user();
		endforeach;
		$html .= "</table>";
		return $html;
	}
	
	public static function view_user_activity($month, $year) {
		global $database;
		$sql  = "SELECT A.author, {$month} as mese, {$year} as anno, count(A.title) as articoli_scritti ";
		$sql .= "from articles as A WHERE month(A.created) = {$month} AND  year(A.created) = {$year} ";
		$sql .= "group by A.author ";
		$users_activity = $database->query($sql);
		$html  = "<h1>Month: {$month}, Year: {$year}</h1>";
		$html .= "<table><tr><td>User</td><td>Articoli Scritti</td></tr>";
		while ($user = mysqli_fetch_assoc($users_activity)) {
		     $html .= "<tr><td>{$user["author"]}</td><td>{$user["articoli_scritti"]}</td></tr>";
		}
		$html .= "</table>";
		return $html;
	}


	public static function user_activity($month, $year) {
		global $database;
		$sql  = "SELECT A.author, {$month} as mese, {$year} as anno, count(A.title) as articoli_scritti ";
		$sql .= "from articles as A WHERE month(A.created) = {$month} AND  year(A.created) = {$year} ";
		$sql .= "group by A.author ";
		return $database->query($sql);
	}
	
	
	public function count_articles() {
		global $database;
		$sql = "SELECT COUNT(*) FROM articles WHERE author='{$this->username}'";
		$result_set = $database->query($sql); //returns it as a row anyway
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	public function count_articles_by_subject() {
		global $database;
		$sql = "SELECT COUNT(*) FROM articles WHERE author='{$this->username}' AND subject='{$subject}'";
		$result_set = $database->query($sql); //returns it as a row anyway
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}

}
?>
<?php
/*IMPORTANT*/
/*array_shift(array) => returns the first element of the array (in this case pulls the first object from an array of User objects)*/
/*get_object_vars($object) => looks at the istance of an object and finds out what are all the attributes (includes the private ones)
 *(for a workaround I might look on php.net)*/
/*array_key_exists($attribute, $object_vars) => true if $attribute exists in $object_vars, else false*/


?>
