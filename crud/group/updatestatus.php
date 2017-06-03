<?php
// check if value was posted
// include database and object file
	include_once '../class/config.php';
	include_once '../class/group.php';

	// get database connection
	$database = new Config();
	$db = $database->getConnection();

	// prepare group object
	$group = new Group($db);
	
	// set grope id to be deleted
	$group->id = isset($_GET['id']) ? $_GET['id'] : die('Need group ID');
	
	$group->readOne();
	
	if($group->status == 1){
		$group->status = 0;
	}else{
		$group->status = 1;
	}
	
	// update group status
 	if($group->updatestatus()){
		echo "<script>location.href='index.php'</script>";
	}
	
	// if unable to delete the product
	else{
		echo "<script>alert('Failed to Deleted Data')</script>";
		
	}
?>