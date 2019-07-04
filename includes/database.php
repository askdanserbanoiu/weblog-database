<?php
/*if (!defined('DB_HOST')) {define("DB_HOST", "localhost");}
if (!defined('DB_NAME')) {define("DB_NAME", "weblogdb");}
if (!defined('DB_USER')) {define("DB_USER", "dan");}
if (!defined('DB_PASS')) {define("DB_PASS", "pass");}*/

if (!defined('DB_HOST')) {define("DB_HOST", "uf63wl4z2daq9dbb.chr7pe7iynqr.eu-west-1.rds.amazonaws.com");}
if (!defined('DB_NAME')) {define("DB_NAME", "pohv8my3aodcjmga");}
if (!defined('DB_USER')) {define("DB_USER", "axet62f7yp9vthny");}
if (!defined('DB_PASS')) {define("DB_PASS", "aldzxm2o0m8z91e9");}

class Database {
	private $dbconn; 
	
	/*CONSTRUCTOR*/
	function __construct() {
		$this->open_connection();
	}
	
	public function open_connection() {
		$this->dbconn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
		die("Database connection failed: " .
			mysqli_connect_error() .
			" (" . mysqli_connect_errno() . ")");
		}
	}
		
	public function close_connection() {
		if (isset($this->dbconn)) {
			mysqli_close($this->dbconn);
			unset($this->dbconn);
		}	
	}
	
	public function query($sql) {
		$result = mysqli_query($this->dbconn, $sql);
		$this->confirm_query($result);
		return $result;
	}
	
	private function confirm_query($result) {
		if (!$result) {
			die("Database query failed.");
		}
	}
	
	public function escape_value($string) {
		return mysqli_real_escape_string($this->dbconn, $string);
	}
	
	/*DATABASE NEUTRAL FUNCTIONS. NOT DEPENDENT ON TYPE OF DB USED*/
	
	public function fetch_array($result_set) {
		return mysqli_fetch_array($result_set);
	}
	
	public function num_rows($result_set) {
	/*number of rows affected in the last sql statement*/
		return mysqli_num_rows($result_set);
	} 
	
	public function insert_id() {
	/*get the last id inserted over the current dbconn*/
		return mysqli_insert_id($this->dbconn);
	}
	
	public function affected_rows() {
	/*number of rows affected in the last sql query*/
		return mysqli_affected_rows($this->dbconn);
	}
}

$database = new Database();
?>
