<?php

if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']) or $_SESSION['role'] != "Admin")  
{
    header("Location: index.php"); 
	exit;  
} 

include_once  'crud/class/config.php';
include_once  'crud/class/user.php';

$database = new Config();
$db = $database->getConnection();

if ($_REQUEST['delete']) {
	
		$user = new User($db);
	
		$user->id = $_REQUEST['delete'];
				
		if ($user->delete()) {
			echo "User Deleted Successfully ...";
		}
		
}