<?php
if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']) or $_SESSION['role'] != "Admin")  
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
include_once  'crud/class/image.php';
 
$database = new Config();
$db = $database->getConnection();

$crudobj = new Crud($db);

$project = new Project($db);


function random_image($db)
{
		
	
	$images = new Image($db);

	$sth = $images->readallimages();
	$allimages = $sth->fetchAll(PDO::FETCH_COLUMN, 0);	

	if(!empty($allimages)){
		$stmp = $images->readimages_tbl_project();									
		$usedimg = $stmp->fetchAll(PDO::FETCH_COLUMN, 0);
												
		//$stmd = $images->readdownimages();
		//$downimage = $stmd->fetchAll(PDO::FETCH_COLUMN, 0);

		$rest_image = array();
		if(count($usedimg) == 0 or count($allimages) == 0){
			$rest_image = $allimages;
		}elseif(count($usedimg) == 0 and count($allimages) > 0){								
			$rest_image = $allimages;
		}elseif(count($usedimg) > 0 and count($allimages) > 0){
			$rest_image = array_diff($allimages, $usedimg);
		}
	
		if(count($rest_image) > 0){
			mt_srand((double)microtime() * 1000000 );
			$num = array_rand($rest_image);
    	
			$images->id = $allimages[$num];
			$imgstm = $images->readone();
    	
			$randimg = $imgstm->fetchAll(PDO::FETCH_ASSOC);
		
			return $randimg;
		}else{
			mt_srand((double)microtime() * 1000000 );
			$num = array_rand($allimages);
		
			$images->id = $allimages[$num];
			$imgstm = $images->readone();
    	
			$randimg = $imgstm->fetchAll(PDO::FETCH_ASSOC);
		
			return $randimg;
		}
	}
}

function check_image($db, $number_image_viewer)
{
		
	
	$images = new Image($db);

	$sth = $images->readallimages();
	$allimages = $sth->fetchAll(PDO::FETCH_COLUMN, 0);	

	if(!empty($allimages)){
		$stmp = $images->readimages_tbl_project();									
		$usedimg = $stmp->fetchAll(PDO::FETCH_COLUMN, 0);

		$rest_image = array();
		if(count($usedimg) == 0 or count($allimages) == 0){
			$rest_image = $allimages;
		}elseif(count($usedimg) == 0 and count($allimages) > 0){								
			$rest_image = $allimages;
		}elseif(count($usedimg) > 0 and count($allimages) > 0){
			$rest_image = array_diff($allimages, $usedimg);
		}
		
		if(count($rest_image) >= $number_image_viewer){	
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function compare_current($dateobj){
	$current = date('Y-m-d H:i:s');
	$current_date = new DateTime($current);
	
	if($dateobj >= $current_date)
		return true;
	else
		return false;
}
function compare_dates($dateobj1, $dateobj2){
	if($dateobj2 >= $dateobj1)
		return true;
	else 
		return false;
}
function formate_date($date_str){
	
    list($month, $day, $year, $hour, $minute, $dayType) = preg_split('/[\/\s:]+/', $date_str); 
    $d1me = $year . '-' . $month. '-' .  $day . ' ' . ($dayType == "PM"?$hour+12: $hour) . ":" . $minute . ":00";

	$dateobj = new DateTime($d1me);
	
	return $dateobj;
}

// set your default timezone
//date_default_timezone_set('Asia/Manila');

?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ARV | Advanced form elements</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/colorpicker/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue layout-boxed">
<div class="wrapper">

  <?php
  	include_once 'header.php';
  ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php
  	include_once 'sidebar.php';
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) 
    <section class="content-header">
      <h1>
        Advanced Form Elements
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
        <li class="active">Advanced Elements</li>
      </ol>
    </section>-->

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Task Setting</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <div class="row">
            <div class="col-md-12">
            <?php
				if (isset($_POST['savestg'])) {
					
					//setting project variable
					$project->Name = htmlspecialchars($_POST['projectname']);
					$project->Question = htmlspecialchars($_POST['question']);
					$project->status = htmlspecialchars($_POST['status']);

					//setting setting variables 
					$Upload_Deadline = htmlspecialchars($_POST['uploadlast']).' '.htmlspecialchars($_POST['time_uploadlast']);
					$Judge_Deadline = htmlspecialchars($_POST['judgetime']).' '.htmlspecialchars($_POST['time_judgetime']);
					$Place_Date_Time = htmlspecialchars($_POST['tradestart']).' '.htmlspecialchars($_POST['time_tradestart']);
					$Exit_Date_Time = htmlspecialchars($_POST['tradeexit']).' '.htmlspecialchars($_POST['time_tradeexit']);
					$Notification_Time = htmlspecialchars($_POST['notiftime']).' '.htmlspecialchars($_POST['time_notiftime']);
					
					$Time_Zone = htmlspecialchars($_POST['time_zone']);
					date_default_timezone_set($Time_Zone);
					$timestamp = date('Y-m-d H:i:s');
					$project->Date_Time = $timestamp;
					
					$project->Upload_Deadline = formate_date($Upload_Deadline);
					$project->Judge_Deadline = formate_date($Judge_Deadline);
					$project->Place_Date_Time = formate_date($Place_Date_Time);
					$project->Exit_Date_Time = formate_date($Exit_Date_Time);
					$project->Notification_Time = formate_date($Notification_Time);
					//('Y-m-d H:i:s')
					
					$project->Investment = 0;//htmlspecialchars($_POST['investment']);
					
					//$pair = htmlspecialchars($_POST['pair']);
					
					if(compare_current($project->Judge_Deadline) and 
						compare_current($project->Upload_Deadline) and 
						compare_current($project->Place_Date_Time) and
						compare_current($project->Exit_Date_Time) and 
						compare_current($project->Notification_Time)){
							if(compare_dates($project->Upload_Deadline, $project->Judge_Deadline)){
								if(compare_dates($project->Judge_Deadline, $project->Place_Date_Time)){
									if(compare_dates($project->Place_Date_Time, $project->Exit_Date_Time)){
										if(compare_dates($project->Exit_Date_Time, $project->Notification_Time)){
											try{
												$pair = 1;
												//if($pair == 1){
												$img_num_viewer = 1;
												//}else{
												//	$img_num_viewer = count($_POST['viewers']); 
												//	echo $img_num_viewer;
												//}
											
												if(check_image($db, $img_num_viewer)){ 
													
													$db->beginTransaction();
													
													$project->Upload_Deadline = $Upload_Deadline;
													$project->Judge_Deadline = $Judge_Deadline;
													$project->Place_Date_Time = $Place_Date_Time;
													$project->Exit_Date_Time = $Exit_Date_Time;
													$project->Notification_Time = $Notification_Time;
													
													
													$projectid = $project->create();
													
													$projectviewers = $_POST['viewers'];
													for ($v = 0; $v < count($projectviewers); $v++) {
														
														$viewer = new ViewerGroup($db);
														$viewer->viewer_id = $projectviewers[$v];
														$viewer->project_id = $projectid;
														$viewer->created = $timestamp;
														$viewer->create();
													}

													$projectjudges = $_POST['judges'];
													for ($j = 0; $j < count($projectjudges); $j++) {
														
														$judge = new JudgeGroup($db);
														$judge->judge_id = $projectjudges[$j];
														$judge->project_id = $projectid;
														$judge->created = $timestamp;
														$judge->create();
													}
													
													$rand_image = random_image($db);
													//$image_down_pair = random_image($db);

													for($isv = 0; $isv < count($projectviewers); $isv++){
														if($pair == 0){
															$rand_image = random_image($db);
														}
														
														for($isj = 0; $isj < count($projectjudges); $isj++){
															$sketch = new SketchJudgeImage($db); 
															$sketch->judgeid = $projectjudges[$isj];
															if($pair == 1){
																$sketch->imageid = $rand_image[0]['Id'];
																$sketch->imageup = $rand_image[0]['Image_Path'];
																$sketch->imagedown = $rand_image[0]['Image_Path2'];	
															}else{
																$sketch->imageid = $rand_image[0]['Id'];
																$sketch->imageup = $rand_image[0]['Image_Path'];
																$sketch->imagedown = $rand_image[0]['Image_Path2'];
															}
															
															$sketch->datetime = $timestamp;
															$sketch->projectid = $projectid;
															$sketch->veiwerid = $projectviewers[$isv];
															
															//var_dump($sketch);
															$s = $sketch->create();
														}
													}
													
													$db->commit();
													
													//$crudobj->createImageSketch(2,9,11,$timestamp,37,9,9);
													if (headers_sent()) {
													?>
													<meta http-equiv="Refresh" content="2; url=compose.php?to=viewer&pid=<?php echo $projectid; ?>">
													<?php
														die("<strong> The task has been created successfully! </strong><br>Please click on this link: <a href='compose.php?to=viewer&pid=".$projectid."'>Compose Massage</a> or click <a href='tasks.php'>All Tasks</a> to view all tasks");
													}
													else{
														exit(header("Location: compose.php?to=viewer&pid=".$projectid));
													}
													
													//exit();
												}else{
												?>
													<div class="alert alert-danger alert-dismissible">
														<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
														<strong> There was a problem in creating the Task. The images set is empty or all image have been used.
														<br> Please update the image set before crating a Image Viewing Task! Please click on this link: <a href='image_add.php'>Image</a></strong>
													</div>
												<?php
												}
												
											} catch (PDOException $ex) {
												//Something went wrong rollback!
												//$db->rollBack();
												print_r($ex->getMessage());
											}

										}else{
											?>
												<div class="alert alert-danger alert-dismissible">
													<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
													<strong> Feedback Date/Time is less than Notification Date/Time. <br> Please specify the correct Date/Time!</strong>
												</div>
											<?php
										}
									}else{
										?>
											<div class="alert alert-danger alert-dismissible">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
												<strong> Tread Exit Date/Time is less than Tread Placement Date/Time. <br> Please specify the correct Date/Time!</strong>
											</div>
										<?php
									}
								}else{
									?>
										<div class="alert alert-danger alert-dismissible">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
											<strong> Tread Placement Date/Time is less than Judging Date/Time. <br> Please specify the correct Date/Time!</strong>
										</div>
									<?php
								}
							}else{
								?>
									<div class="alert alert-danger alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<strong> Judging Date/Time is less than Image Upload Date/Time. <br> Please specify the correct Date/Time!</strong>
									</div>
								<?php
							}
					}else{
						?>
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<strong> All of the Date/Time should be greater than the Current Date/Time. <br> Please specify the correct Date/Time!</strong>
							</div>
						<?php
					}
				}
			?>
            
             <h4 class="box-title">Task Basic Info</h4>	
            	
            	
					<form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Task Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="projectname" id="project-name" required="required" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="textarea">Description <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea id="textarea" required="required" name="question" class="form-control col-md-7 col-xs-12"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-primary active">
										<input type="radio" name="status" id="option1" value="1" autocomplete="off" checked> Active
									</label>
									<label class="btn btn-primary">
										<input type="radio" name="status" id="option2" value="0" autocomplete="off"> Inactive
									</label>
								</div>
							</div>
						</div>
                                            <!--<div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Investment (%) <span class="required"></span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input id="investment" name="investment" class="date-picker form-control col-md-7 col-xs-12" type="text">
                                                </div>
                                            </div> -->
											
                                            <!--<hr>
                               				<h4 class="box-title">Photo Target Pair mode</h4>	
                               				
                               				<div class="form-group">
                                            	<label class="control-label col-md-3 col-sm-3 col-xs-12">Photo target pair</label>
                                            	<div class="col-md-6 col-sm-6 col-xs-12">
                                            		<div class="btn-group" data-toggle="buttons">
  														<label class="btn btn-primary active">
    														<input type="radio" name="pair" id="option1" value="1" autocomplete="off" checked> Same Pair
  														</label>
  														<label class="btn btn-primary">
    								   						<input type="radio" name="pair" id="option2" value="0" autocomplete="off"> Diffrent Pair
  														</label>
                                            		</div>
                                            	</div>
                                            </div>-->

						<?php
						$viewer = new Viewer($db);
						$judge = new Judge($db);

						$stmt = $viewer->readAllViewer();
						$viewernum = $stmt->rowCount();
						$jstmt = $judge->readAllJudge();
						?>

						<hr>
						<h4>Task Member</h4>
						<div class="ln_solid"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Image Viewers</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="viewers[]" class="js-switch select2_multiple form-control" multiple="multiple" required="required">
									<?php
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										extract($row);
										echo "<option value='{$id}'>{$first_name} &nbsp; {$last_name}</option>";
									}
									?>  
								</select>
							</div>
						</div>

						<div class = "form-group">
							<label class = "control-label col-md-3 col-sm-3 col-xs-12">Image Judges</label>
							<div class = "col-md-6 col-sm-6 col-xs-12">
								<select name = "judges[]" class = "js-switch select2_multiple form-control" multiple = "multiple" required="required">
									<?php
									if ($viewernum != 0) {
										while ($row = $jstmt->fetch(PDO::FETCH_ASSOC)) {
											extract($row);
											echo "<option value='{$id}'>{$first_name} &nbsp; {$last_name}</option>";
										}
									} else {
										echo "<option>NO Judge</option>";
									}
									?> 
								</select>
							</div>
						</div> 
				</div>
			</div>
		<hr>
			<div class="row">
				<div class="col-md-12">
					<h4>Task Time Setting</h4>
					<div class="ln_solid"></div>
				</div>          
				<br>                         
				<div class="col-md-6">
						  <!-- Viewer Sketch Deadline Date -->
						<?php
							function tz_list() {
							  $zones_array = array();
							  $timestamp = time();
							  foreach(timezone_identifiers_list() as $key => $zone) {
								date_default_timezone_set($zone);
								$zones_array[$key]['zone'] = $zone;
								$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
							  }
							  return $zones_array;
							}
						?>
							<div class="form-group">
							<label>Time Zone:</label>
							<select class ="form-control" name="time_zone">
								<option value="America/New_York">
									<?php 
										$timestamp = time();
										date_default_timezone_set("America/New_York"); 
										echo 'UTC/GMT ' . date('P', $timestamp);
									?>
									&nbsp; - America/New_York
								</option>
								<?php foreach(tz_list() as $t) { ?>
								  <option value="<?php print $t['zone'] ?>">
									<?php print $t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
								  </option>
								<?php } ?>
							 </select>
						  </div>
						  
						  <div class="form-group">
							<label>Sketch Upload Deadline:</label>

							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input id="uploadlast" name="uploadlast" type="text" class="form-control pull-right" required="required">
							</div>
							<!-- /.input group -->
						  </div>
						  <!-- /.Viewer Sketch Deadline Date form group -->
										<!-- Viewer Sketch Deadline Date -->
						  <div class="form-group">
							<label>Judge Deadline *</label>

							<div class="input-group date">
								<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
								</div>
								<input id="judgetime" name="judgetime" type="text" class="form-control pull-right" required="required">
							</div>
							<!-- /.input group -->
						  </div>
						  <!-- /.Judge Deadline Date form group -->
						  <!-- Trade Time Start -->
						  <div class="form-group">
							<label>Trade Time Start *</label>
							<div class="input-group date">
								<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
								</div>
								<input id="tradestart" name="tradestart" type="text" class="form-control pull-right" required="required">
							</div>
							<!-- /.input group -->
						  </div>
						  <!-- /.Trade Time Start form group -->
						  <!-- Trade Exit Time Date -->
						  <div class="form-group">
							<label>Trade Exit Time *</label>
							<div class="input-group date">
							  <div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							  </div>
							  <input id="tradeexit" name="tradeexit"  type="text" class="form-control pull-right" required="required">
							</div>
							<!-- /.input group -->
						  </div>
						  <!-- /.Trade Exit Time Date form group -->
						  <!-- Notification Time Date -->
						  <div class="form-group">
							<label>Notification Time *</label>
							<div class="input-group date">
							  <div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							  </div>
							  <input id="notiftime" name="notiftime" type="text" class="form-control pull-right" required="required">
							</div>
							<!-- /.input group -->
						  </div>
						  <!-- /.Notification Time * Date form group -->
					</div>
						<div class="col-md-6">
							<div class="form-group"> 
								<label>&nbsp;</label>
								<div class="input-group">&nbsp; </div>
							<div> <br> </div>
							</div>
								  <!-- time Picker -->
						  <div class="bootstrap-timepicker">
							<div class="form-group">
							  <label>Time:</label>
							  <div class="input-group">
								<div class="input-group-addon">
								  <i class="fa fa-clock-o"></i>
								</div>
								<input id="time_uploadlast" name="time_uploadlast" type="text" class="form-control timepicker">
							  </div>
							  <!-- /.input group -->
							</div>
							<!-- /.form group -->
						  </div>
						   <div class="bootstrap-timepicker">
							<div class="form-group">
							  <label>Time:</label>
							  <div class="input-group">
									<div class="input-group-addon">
									  <i class="fa fa-clock-o"></i>
									</div>
								<input id="time_judgetime" name="time_judgetime" type="text" class="form-control timepicker">
							  </div>
							  <!-- /.input group -->
							</div>
							<!-- /.form group -->
						  </div>
						   <div class="bootstrap-timepicker">
							<div class="form-group">
							  <label>Time:</label>
							  <div class="input-group">
									<div class="input-group-addon">
									  <i class="fa fa-clock-o"></i>
									</div>
								<input id="time_tradestart" name="time_tradestart" type="text" class="form-control timepicker">
							  </div>
							  <!-- /.input group -->
							</div>
							<!-- /.form group -->
						  </div>
						   <div class="bootstrap-timepicker">
							<div class="form-group">
							  <label>Time:</label>
							  <div class="input-group">
									<div class="input-group-addon">
									  <i class="fa fa-clock-o"></i>
									</div>
								<input id="time_tradeexit" name="time_tradeexit" type="text" class="form-control timepicker">
							  </div>
							  <!-- /.input group -->
							</div>
							<!-- /.form group -->
						  </div>
						   <div class="bootstrap-timepicker">
							<div class="form-group">
							  <label>Time:</label>
							  <div class="input-group">
									<div class="input-group-addon">
									  <i class="fa fa-clock-o"></i>
									</div>
									<input id="time_notiftime" name="time_notiftime" type="text" class="form-control timepicker">
							  </div>
							  <!-- /.input group -->
							</div>
							<!-- /.form group -->
						  </div>
				</div>
			</div>
			<hr>
			<div class="row">
            <div class="col-md-12">                               
			<div class = "ln_solid"></div>
			<div class = "form-group">
				<div class = "col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
					
					<button name = "savestg" type = "submit" class = "btn btn-success">Create Task</button>
					<button type = "submit" class = "btn btn-primary">Cancel</button>
				</div>
			</div>

			</form>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body 
        <div class="box-footer">
          Visit <a href="https://select2.github.io/">Select2 documentation</a> for more examples and information about
          the plugin.
        </div>-->
      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Control Sidebar -->
  <?php include 'footer.php'; ?>
  <!-- Control Sidebar -->
  <?php include_once 'ControlSidebar.php' ?>
  <!-- end of control sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<!-- select2 scripte -->
<link href="plugins/select2/select2.min.css" rel="stylesheet">

<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#uploadlast').datepicker({
      autoclose: true
    });
    
    //Date picker
    $('#judgetime').datepicker({
      autoclose: true
    });
    
    //Date picker
    $('#tradestart').datepicker({
      autoclose: true
    });
    
    //Date picker
    $('#tradeexit').datepicker({
      autoclose: true
    });
    
    //Date picker
    $('#notiftime').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>
<!-- select2 -->
<script>
	$(document).ready(function () {
		$(".select2_single").select2({
			placeholder: "Select a state",
			allowClear: true
		});
		$(".select2_group").select2({});
		$(".select2_multiple").select2({
			maximumSelectionLength: 10,
			placeholder: "With Max Selection limit 10",
			allowClear: true
		});
	});
</script>
<!-- /select2 -->
</body>
</html>
