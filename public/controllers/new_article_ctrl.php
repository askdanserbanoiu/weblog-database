<?php 
require_once("../includes/initialize.php"); 

if (isset($_POST["submit"])) {
	$title = $_POST["title"];
	$message="allgood";
	if (isset($_FILES["article_img_id"]) ) {
		$article_pic = new Image($_FILES["article_img_id"]); 
		if ($article_pic->save($title)) {
			$article_pic_id = (int)$article_pic->get_img_id();
			$created = date("Y-m-d H:i:s"); 
			$body = $_POST["body"];
			$author = $session->user_id;
			
			/*SAVE ARRAY OF SUBJECTS*/
			$subjects = $_POST["subjects"];
			$i = 0; 
			foreach ($subjects as $subject) {
				$subjects[$i] = $subject;
				$i = $i + 1;
			}
			
							
			$article = new Article($title, $article_pic_id, $body, $author, $created, $subjects);
			if ($article->create()) {
				/**/update_queries("create_article"); /**/
				$message = "article created";
			}
			else {
				$message = "article creation failed";
			}
		}
		else {
			$message = "problem uploading image";
		}
	}
	else $message= "add an image to the article!";
}
?>