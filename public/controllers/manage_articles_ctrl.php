<?php 
require_once("../includes/initialize.php"); 
 
$author = null;
$subject = null; 
$month = null;
$year = null;
if (isset($_GET["author"])) $author = $_GET["author"];  
if (isset($_GET["subject"])) $subject = $_GET["subject"];  
if (isset($_GET["date"])) {
    $dateValue = strtotime($_GET["date"]);
    $month = date("m", $dateValue);
    $year = date("Y", $dateValue); 
}  


if ($root) {
    $pagination = new Pagination(!(isset($_GET['page']))? 1 : $_GET['page'], 5, Article::count_by($author, $subject, $month, $year));
    $articles_list = Article::view_current_page_articles($pagination, $author, $subject, $month, $year, true); 
}
else {
    $pagination = new Pagination(!(isset($_GET['page']))? 1 : $_GET['page'], 5, Article::count_by($user->get_username(), $subject, $month, $year));
    $articles_list = Article::view_current_page_articles($pagination, $user->get_username(), $subject, $month, $year, true);
}


?>