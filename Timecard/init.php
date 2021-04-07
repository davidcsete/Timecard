<?php
// Initialize the session
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("DB_HOST", "localhost");
define("DB_USER" , "root");
define("DB_PW","");
define("DB_NAME","timecard_db");

$GLOBALS['con'] = mysqli_connect(DB_HOST,DB_USER,DB_PW,DB_NAME);

if ($con->connect_error) 
{
  die("Connection failed: " . $con->connect_error);
}



require_once "class/Felhasznalo.php"; 
$felhasznalo = new Felhasznalo();
/*else
{
    echo "Connection succesfull!";
}*/
