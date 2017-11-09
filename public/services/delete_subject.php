<?php require_once("../../includes/initialize.php"); ?>
<?php 
	/*DELETE ADMIN*/
	$subject = Subject::find_by_id($_GET["subject"]);
	$result = $subject->delete();
	
	if ($result) {
		/*QUERY WAS A SUCCESS AND THE AFFECTED ROWS ARE 1*/
		$_SESSION["message"] = "subject deleted.";
		redirect_to("../manage_subjects.php");
	}
	else {
		$_SESSION["message"] = "subject {$_GET["subject"]} deletion failed, actively used to group articles";
		redirect_to("../manage_subjects.php");
	}
?>