<?php require_once("../../includes/initialize.php"); ?>
<?php

if (isset($_POST["submit"])) {
	$session->logout();
	redirect_to("../articles.php");
}

?>