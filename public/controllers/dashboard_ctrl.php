<?php 
require_once("../includes/initialize.php"); 
 
$month = date('n'); 
$year = date('Y'); 

if (isset($_GET["date"])) {
    $dateValue = strtotime($_GET["date"]);
    $month = date("m", $dateValue);
    $year = date("Y", $dateValue); 
}  	

$activity_list = User::user_activity($month, $year);      
$stats_list = query_history($month, $year);;      
$most_commented = Article::most_commented($month, $year, true);
?>