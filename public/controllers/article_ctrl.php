<?php 
require_once("../includes/initialize.php"); 

$article = Article::find_by_id($_GET["article_id"]); 

if ($session->is_logged_in()) {
	$avatar = Image::find_by_id($user->user_img_id);
}

$img = Image::find_by_id($article->article_img_id); 

if (isset($_POST["submit"])){
	update_queries("view_article");
	update_queries("comment_article"); 
	
	$comment = new Comment($_POST["author"], $_GET["article_id"], $_POST["body"]);
	if ($comment->create()) {
		$message = "Commento creato";
	}
	else {
		$message = "Commento non creato";
	}
}

$subjects = Subject::find_all();
$users = User::find_all();


?>