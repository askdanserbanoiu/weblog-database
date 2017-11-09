<?php
require_once(LIB_PATH.DS."subject.php");
require_once(LIB_PATH.DS."article.php");
require_once(LIB_PATH.DS."database.php");


function redirect_to($location = NULL) {
	if ($location != NULL) {
		header("Location: {$location}");
		exit;
	}
}

/*genera una stringa univoca casuale con dimensione=$length*/
function generateRandomUniqueString($length)
{   
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $key = "";
    for($i=0; $i<$length; $i++) 
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))]; 

    return $key;
}

/*LOGIN BOX HTML*/
function login_box() {
	$html  = "<form action=\"control/login.php\" method=\"post\">";
	$html .= "<h1>LOGIN: </h1>";
	$html .= "<p>username: <input type=\"text\" name=\"username\" placeholder=\"username\" /></p>";
	$html .= "<p>password: <input type=\"password\" name=\"password\" placeholder=\"password\" /></p>";
	$html .= "<input type=\"submit\" name=\"submit\" value=\"login\" />";
	$html .= "</form>";
	return $html;	
}

/*USER PROFILE BOX HTML*/
function user_profile_box($user, $control=false) {
	$html  = "<h1>{$user->get_fullname()}</h1>";
	if ($control) $html .= "<div><img src=\"../images/{$user->get_user_img_filename()}\" /><span class=\"helper\"></span></div>";
	else $html .= "<div><img src=\"images/{$user->get_user_img_filename()}\" /><span class=\"helper\"></span></div>";
	$html .= "<p>permission: {$user->get_permission()}</p>";
	if ($control) $html .= "<form action=\"logout.php\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"logout\" /></form>";
	else $html .= "<form action=\"control/logout.php\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"logout\" /></form>";
	return $html;	
}

function navbar_generator($user=false, $root=true) {
	$path = "articles.php";
	if ($user) $path = "manage_articles.php";
	$html  = "<form action=\"{$path}\" method=\"get\">";
	if ($root){
		$html .= "<h1>BY AUTHOR</h1>";
		$users = User::find_all();
		$html .= "<ul>";
		foreach ($users as $user) {
			$html .= "<li><input type=\"radio\" name=\"author\" value=\"{$user->get_username()}\">";
			$html .= "<a href=\"{$path}?author={$user->get_username()}\">{$user->get_username()}</a></li>";
		}
		$html .= "</ul>";
	}
	$html .= "<h1>BY SUBJECT</h1>";	
	$subjects = Subject::find_all();
	$html .= "<ul>";
	foreach ($subjects as $subject) {
		$html .= "<li><input type=\"radio\" name=\"subject\" value=\"{$subject->subject}\">";
		$html .= "<a href=\"{$path}?subject={$subject->subject}\">{$subject->subject}</a></li>";		
	}
	$html .= "</ul>";
		$html .= "<h1>BY MONTH</h1>";	
	$html .= "<select name=\"month\">";
	$html .= "<option disabled selected> -- select a month -- </option>";
	for ($m=0; $m < 13; $m++) {
		$html .= "<option value=\"{$m}\">{$m}</option>";		
	}
	$html .= "</select>";
		$html .= "<h1>BY YEAR</h1>";	
	$html .= "<select name=\"year\">";
	$html .= "<option disabled selected> -- select a year -- </option>";
	for ($y=2016; $y >= Article::get_oldest_year(); $y--) {
		$html .= "<option value=\"{$y}\">{$y}</option>";		
	}
	$html .= "</select>";
	$html .= "<input type=\"submit\" name=\"submit\" value=\"search\" />"; 
	$html .= "</form>";
	return $html;
}

function navbar_generator_bydate($path) {
	$html  = "<form action=\"{$path}\" method=\"get\">";
	$html .= "<h1>BY MONTH</h1>";	
	$html .= "<select name=\"month\">";
	$html .= "<option disabled selected> -- select a month -- </option>";
	for ($m=0; $m < 13; $m++) {
		$html .= "<option value=\"{$m}\">{$m}</option>";		
	}
	$html .= "</select>";
	$html .= "<h1>BY YEAR</h1>";	
	$html .= "<select name=\"year\">";
	$html .= "<option disabled selected> -- select a year -- </option>";
	for ($y=2016; $y >= Article::get_oldest_year(); $y--) {
		$html .= "<option value=\"{$y}\">{$y}</option>";		
	}
	$html .= "</select>";
	$html .= "<p><input type=\"submit\" name=\"submit\" value=\"search\" /></p>"; 
	$html .= "</form>";
	return $html;
}


function update_queries($name) {
	global $database;
	$created = date("Y-m-d H:i:s");
	$sql = "INSERT INTO queries(name, created) VALUES ('{$name}', '{$created}');";
	if ($database->query($sql)) { //true or false
		return true;
	} else {
		return false;
	}
}

function view_query_history($month, $year) {
		global $database;
		$sql  = "select Q.name, count(Q.id) as n_queries from queries as Q WHERE month(created) = {$month} AND year(created) = {$year} group by Q.name ";
		$query_activity = $database->query($sql);
		$html  = "<h1>month: {$month}, year: {$year}</h1>";
		$html .= "<table><tr><td>Query Name</td><td>How many times used</td></tr>";
		while ($user = mysqli_fetch_assoc($query_activity)) {
		     $html .= "<tr><td>{$user["name"]}</td><td>{$user["n_queries"]}</td></tr>";
		}
		$html .= "</table>";
		return $html;
}

function query_history($month, $year) {
		global $database;
		$sql  = "select Q.name, count(Q.id) as n_queries from queries as Q WHERE month(created) = {$month} AND year(created) = {$year} group by Q.name ";
		return $database->query($sql);
	}

?>