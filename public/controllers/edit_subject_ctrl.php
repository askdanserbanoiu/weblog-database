<?php 
require_once("../includes/initialize.php"); 


$subject = Subject::find_by_id($_GET["subject"]);
if (isset($_POST["submit"])) {
	$subject->set_description($_POST["description"]); 
	if ($subject->update()) {
		$message = "La categoria è stata modificata";
		$_POST = array();
	}
	else {
		$message = "La modifica della categoria è fallita";
	}
}
?>