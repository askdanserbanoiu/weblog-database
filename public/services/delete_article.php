<?php require_once("../../includes/initialize.php"); ?>
<?php 
	/*DELETE USER*/
	$article = Article::find_by_id($_GET["article"]);
	$article_img = Image::find_by_id($article->get_article_img_id());
	
    $article->delete();
	$article_img->destroy();	
	redirect_to("../manage_articles.php");
?>