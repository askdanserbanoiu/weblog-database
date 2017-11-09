<?php require_once("../../includes/initialize.php"); ?>
<?php 
	/*DELETE USER*/
	$user = User::find_by_id($_GET["username"]);
	$user_img = Image::find_by_id($user->get_user_img_id());
	
    $user->delete();
	$user_img->destroy();	
	redirect_to("../manage_users.php");
?>