<?php 
require_once("../includes/initialize.php"); 

if ($session->is_logged_in()) {
	$user = User::find_by_id($session->user_id); 
	$root = true;
	if ($user->get_permission() == "admin") {
		$root = false;
	}
}

?>