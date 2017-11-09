<?php 
if (isset($_POST["submit"])) {
	$username = $_POST["username"];
	$password = $_POST["password"];
	$password_re = $_POST["password_re"];
	if ($password == $password_re){
		$name = $_POST["name"];
		$surname = $_POST["surname"];
		$birth = $_POST["birth"];
		$address = $_POST["address"];
		$user_img = new Image($_FILES["user_img"]); 
		if ($user_img->save($username)) {
			$user_img_id = $user_img->get_img_id();
			$user_new = new User($username, $password, $name, $surname, $birth, $address, $user_img_id);
			if ($user_new->save()){
				$message = "L'utente è stato creato";
			}
			else {
				$message = "La creazione dell'utente è fallita";
			}
		}
		else {
			$message = "C'è stato un problema nel caricare l'immagine";
		}
	}
	else {
		$message = "Password non coincidono";
	}
}
?>