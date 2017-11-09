<?php
ob_start(); 

/*DIRECTORY_SEPARATOR e' una costante predefinita di PHP (\ in Windows, / in Unix)*/
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

//defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'Applications'.DS.'MAMP'.DS.'htdocs');
defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'app');


defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

//load basic functions next so that everything after can use them
require_once(LIB_PATH.DS."functions.php");

//load core objects
require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."pagination.php");


//load database_related classes
require_once(LIB_PATH.DS."subject.php");
require_once(LIB_PATH.DS."article.php");
require_once(LIB_PATH.DS."user.php");
require_once(LIB_PATH.DS."image.php");
require_once(LIB_PATH.DS."comment.php");


?>