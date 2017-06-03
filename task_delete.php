<?php
if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']) and $_SESSION['role'] != "Admin")  
{
    header("Location: index.php"); 
	exit;  
} 

include_once  'crud/class/config.php';
include_once  'crud/class/viewer.php';
include_once  'crud/class/judge.php';
include_once  'crud/class/crud.php';
include_once  'crud/class/project.php';
include_once  'crud/class/setting.php';
include_once  'crud/class/viewergroup.php';
include_once  'crud/class/judgegroup.php';
include_once  'crud/class/sketch_image.php';

$database = new Config();
$db = $database->getConnection();

//crude object creation
$crudobj = new Crud($db);

$viewer = new ViewerGroup($db);
$judge = new JudgeGroup($db);
$img_judge_viwer = new SketchJudgeImage($db);

//project object creation 
$project = new Project($db);
	if ($_REQUEST['delete']) {
			
		$projectid = $_REQUEST['delete'];
		$project->id = $projectid;
		
		$viewer->project_id = $projectid;
		$judge->project_id = $projectid;
		$img_judge_viwer->projectid = $projectid;
		//$project->delete();

		
		if ($project->delete()){
			if($viewer->delete_by_viewer()){
				 if($judge->delete_by_project()){
				 	if($img_judge_viwer->delete_judge_viewer_iamge()){
				 		echo "Project Deleted Successfully ...";	
				 	}else{
				 		echo "Project is deleted successfully but there some things left";	
				 	}
				 }else{
				 	echo "Project is deleted successfully but there some things left";
				 }
			}else{
				echo "Project is deleted successfully but there some things left";
			}
		}else{
			echo "Projectis is Not Deleted Successfully ...";
		}
	}