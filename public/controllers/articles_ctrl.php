<?php 

require_once("../includes/initialize.php");  

if ($session->is_logged_in()) {
	$avatar = Image::find_by_id($user->user_img_id);
}


$author = null;
$subject = null; 
$month = null;
$year = null;
if (isset($_GET["author"])) $author = $_GET["author"];  
if (isset($_GET["subject"])) $subject = $_GET["subject"];  
if (isset($_GET["month"])) $month = $_GET["month"];  
if (isset($_GET["year"])) $year = $_GET["year"];  

$pagination = new Pagination(!(isset($_GET['page']))? 1 : $_GET['page'], 6, Article::count_by($author, $subject, $month, $year));

$articles_list = Article::view_current_page_articles($pagination, $author, $subject, $month, $year);

?>