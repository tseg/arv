<?php 
error_reporting(~E_NOTICE);
if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']) or $_SESSION['role'] != "Admin")  
{
    header("Location: index.php"); 
	exit;  
} 

require_once 'dbconfig.php';
include_once  'crud/class/config.php';
include_once  'crud/class/viewer.php';
include_once  'crud/class/judge.php';
include_once  'crud/class/image.php';
include_once  'crud/class/project.php';
include_once  'crud/class/setting.php';
include_once  'crud/class/viewergroup.php';
include_once  'crud/class/judgegroup.php';
 
$database = new Config();
$db = $database->getConnection();

//if(isset($_GET['delete_id']))
// {
if($_REQUEST['delete_id']){
	
	$image_id = $_REQUEST['delete_id'];
	
  	// select image from db to delete
  	$stmt_select = $db->prepare('SELECT Image_Path, Image_Path2 FROM tbl_image WHERE Id =:id');
  	$stmt_select->execute(array(':id'=>$image_id));
  	$imgRow = $stmt_select->fetch(PDO::FETCH_ASSOC);
  	unlink("upload/".$imgRow['Image_Path']);
  	unlink("upload/".$imgRow['Image_Path2']);
  
  	// it will delete an actual record from db
  	$stmt_delete = $DB_con->prepare('DELETE FROM tbl_image WHERE Id =:id');
 	 $stmt_delete->bindParam(':id',$image_id);
  	if($stmt_delete->execute()){
  		echo "Images Deleted Successfully ...";
  	}
  
  //header("Location: image_gallery.php");
  //header("Refresh: 5; url=image_gallery.php");
 }
 
?>