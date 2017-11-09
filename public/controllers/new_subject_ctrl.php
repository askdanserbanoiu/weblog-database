<?php 

require_once("../includes/initialize.php"); 

if (isset($_POST["submit"])) {
	$subject = $_POST["title"];
	$description = $_POST["description"];
	
	$subject = new Subject($subject, $description); 
	if ($subject->create()) {
		$message = "La categoria è stata creata";
	}
	else {
		$message = "La creazione della categoria è fallita";
	}
}

?>