<?php

class Session {
	
	/*SO I CAN'T SAY FROM WITHIN THE APP: $session->logged_in = true*/
	private $logged_in = false;
	public $user_id;
	public $message;
	
	function __construct(){
		session_start(); //su ogni pagina verra' inizializzata la $_SESSION e potro' tirarvi fuori e inserirvi dati
		$this->check_message(); //controllo 
		$this->check_login(); //controlo
	}
	
	/*TRUE if $logged_in = true, else FALSE*/
	public function is_logged_in() {
		return $this->logged_in;
	}
	
	public function login($user) {
		if ($user) {
			$_SESSION['user_id'] = $user->get_username();
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		}
	}
	
	public function logout() {
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$this->logged_in = false;
	}
	
	public function message($msg="") {
		if (!empty($msg)) {
			$_SESSION['message'] = $msg;
		} else {
			return $this->message;
		}
	}
	
	private function check_login() {
		if (isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		}
		else {
			unset($this->user_id);
			$this->logged_in = false;
		}
	}
	
	private function check_message() {
		if (isset($_SESSION['message'])) {
			$this->message = $_SESSION['message'];
			unset($_SESSION['message']);
		} else {
			$this->message = "";
		}
	}
}

/*I MUST INSTANTIATE SESSION IN ORDER TO BE ABLE TO PULL DATA FROM IT*/
$session = new Session();
$message = $session->message();

?>
