<?php 
require_once("controllers/login_ctrl.php"); 
?>


<!DOCTYPE html>
<html>
<head>
    <title>WEBLOG</title>
    <link rel="stylesheet" type="text/css" href="assets/css-custom/login.css">
</head>
<body> 

<div class="login">
    <div class="login-screen">
        <div class="app-title">
            <h1>Login</h1>
        </div>
        <form  class="login-form" action="login.php" method="POST">

            <div class="control-group">
            <input type="text" class="login-field" name="username" value="" placeholder="username" id="login-name">
            <label class="login-field-icon fui-user" for="login-name"></label>
            </div>

            <div class="control-group">
            <input type="password" class="login-field" name="password" value="" placeholder="password" id="login-pass">
            <label class="login-field-icon fui-lock" for="login-pass"></label>
            </div>

            <button type="submit" name="submit" value="login" class="btn btn-primary btn-large btn-block">Login</button>
        </form>
    </div>
</div>
</body>
</html>