<?php 
require_once("../includes/initialize.php"); 

$avatar = Image::find_by_id($user->user_img_id);

if (isset($_POST["submit"])) {
    if ($_POST["submit"] == 'edit_info') {
        $user->name = $_POST["name"];
        $user->surname = $_POST["surname"];
        $user->birth = $_POST["birth"];
        $user->address = $_POST["address"];
        $user->update();
        $message = "Informazioni di Base Modificate!";
    }
    if ($_POST["submit"] == 'edit_avatar') {
        if (isset($_FILES["user_img"]) && $_FILES["user_img"]["size"] != 0) {
             $id_img = Image::save($_FILES["user_img"]);
        }
        $user->update();   
    }
    if ($_POST["submit"] == 'edit_password') {
        if ($user->hashed_password == md5($_POST["old_password"])) {
            if ($_POST["new_password"] == $_POST["re_password"]) {
                $user->hashed_password = md5($_POST["new_password"]);
                $user->update();
                $message = "Password Modificata con Successo!";
            } else {
                $message = "Password Non Coincidono!";
            }
        } else {
            $message = "Vecchia Password Non Giusta!";
        }  
    }
}

		
?>
