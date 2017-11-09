<?php 
require_once("../includes/initialize.php"); 

if (isset($_POST["submit"])) {
    /**/update_queries("login"); 
    $username = $_POST["username"];
    $password = $_POST["password"];
    $user = User::authenticate($username, $password);
    $session->login($user); 
    redirect_to("dashboard.php");
}
?>