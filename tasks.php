		<?php
		if(empty($_SESSION))
			session_start();
		if(!isset($_SESSION['role']) or $_SESSION['role'] != "Admin")  
		{
			header("Location: index.php"); 
			exit;  
		} 

		include_once 'crud/class/config.php';
		include_once 'crud/class/project.php';
		include_once 'crud/class/sketch_image.php';


		$database = new Config();
		$db = $database->getConnection();

		function calculate_img1avgRating($pid){
			
		$database = new Config();
		$db = $database->getConnection();

		$project_rating = new SketchJudgeImage($db);

		$project_rating->projectid = $pid;

		$pstmt = $project_rating->read_project_rating();

		$ImgSum = 0;
		$count = 0;
			while($row = $pstmt->fetch(PDO::FETCH_ASSOC)){
				//var_dump($row);
				$ImgSum = $ImgSum + ($row['Rate_Up'] * calculate_Confidence($row['VeiwerId']));
				$count = $count + 1;
			}
			
			
		//echo "sume of the rate".$ImgSum."</br>";
		//echo "count of row is".$count."</br>";
		//echo "img 1 average value ".calculate_average($ImgSum, $count);

			return calculate_average($ImgSum, $count);
		}



		//calculate_img1avgRating();

		function calculate_img2avgRating($pid){
		$database = new Config();
		$db = $database->getConnection();

		$project_rating = new SketchJudgeImage($db);

		$project_rating->projectid = $pid;

		$pstmt = $project_rating->read_project_rating();

		$ImgSum = 0;
		$count = 0;
			while($row = $pstmt->fetch(PDO::FETCH_ASSOC)){
				$ImgSum = $ImgSum + ($row['Rate_Down'] * calculate_Confidence($row['VeiwerId']));
				$count = $count + 1;
			}

		//echo "sume of the rate".$ImgSum."</br>";
		//echo "count of row is".$count."</br>";

		//echo " <br> img 2 average value ".calculate_average($ImgSum, $count);
			return calculate_average($ImgSum, $count);
		}





		//calculate_img2avgRating();
		function calculate_Confidence($viewerId){
			
		$database = new Config();
		$db = $database->getConnection();

		$project = new Project($db);
			
		$stmt = $project->readProjectDirection($viewerId);

		$ConfSum = 0;
		$count = 0;

		//$row = $stmt->fetch(PDO::FETCH_ASSOC);
		//var_dump($row);

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				//var_dump($row);
				if($row['Online_Trade_Direction'] == $row['Judge_Direction']){
					//echo "in true";
					$ConfSum = $ConfSum + 1;
				}
				
				if($count == 3)
					break;
				$count = $count + 1;
			}
			
			//echo "conf of v".$viewerId." is ".calculate_average($ConfSum, $count)."<br>";
			return calculate_average($ConfSum, $count);
		}


		function calculate_average($valus = 0, $count = 0){

			return $avg = ($count != 0)?$valus/$count:$valus;
		}

		function Average_Rating($pid){
			return calculate_img1avgRating($pid) - calculate_img2avgRating($pid);
		}

		function determain_direction($pid){
			
			$AvgRating =  Average_Rating($pid);
			
			if($AvgRating >= 1.5 and $AvgRating <= 4){
				return "UP";
			}else if($AvgRating < 1.5 and $AvgRating > -1.5){
				return "NO TRAD";
			}else if($AvgRating <= -1.5 and $AvgRating >= -4){
				return "DOWN";
			}
		}


		function investment($pid){
			
			$AvgRating =  Average_Rating($pid);
			
			if($AvgRating < 0){
				return (((-1 * $AvgRating) - 1.5)/2.5) * (1/3);
			}else if($AvgRating > 1){
				return (($AvgRating - 1.5)/2.5) * (1/3);	
			}
		}

		function getAccount(){
			return 100;
		}

		//determain_direction();


		function getData($db) {
			$stmt = $db->query("SELECT * FROM tbl_project");
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		//var_dump(getData($db));


		$mcounter=0;


		?>


		<!DOCTYPE html>
		<html>
		<head>
		  <meta charset="utf-8">
		  <meta http-equiv="X-UA-Compatible" content="IE=edge">
		  <title>ARV | View All Tasks Page</title>
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

		  <!-- header start -->
		  <?php
		  include_once 'header.php';
		  ?>
		  <!-- end of header -->
		  <!-- Left side column. contains the logo and sidebar -->
		  <?php
			include_once 'sidebar.php';
		  ?>
		  <!-- end of side bare -->

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
				  <h3 class="box-title">All Tasks Table</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				  </div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				  <div class="row">
					<div class="col-md-12">
						<table id="datatable" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th style="width: 1%">#</th>
									<th style="width: 20%">Project Name</th>
									<th style="width: 30%">Participants</th>
									<th>Investment</th>
									<th>Avg Rating</th>
									<th style=" width: 1%">Judge Direction</th>
									<th style=" width: 10%">Trade Direction</th>
									<th style=" width: 10%">Status</th>
									<th style=" width: 20%">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($db->query('SELECT * FROM tbl_project') as $row) {
									$mcounter++;
									?>
									<tr>
										<td><?php echo $mcounter;?></td>
										<td>
											<a><?php echo $row['Name'];$ppid=$row['Id']; ?></a>
											<br />
											<small><?php echo $row['Date_Time']; ?></small>
										</td>
										<td>
											<button class="btn btn-app" onclick="window.location.href='task_viewers.php?edit_id=<?php print($ppid); ?>'">
												<span class="badge bg-green">
												<?php
												   
													$stmt = $db->prepare("SELECT * FROM tbl_viewer_group WHERE Project_Id=?");
													$stmt->execute(array($row['Id']));
													$row_count = $stmt->rowCount();
													$xx=$row_count;
													echo $row_count; 
																																															
													?></span>
												<i class="fa fa-users"></i> 
												Viewer																
											</a>

											<button class="btn btn-app" onclick="window.location.href='task_judges.php?edit_id=<?php print($ppid); ?>'">
											<span class="badge bg-blue">
											
											<?php
													$stmt = $db->prepare("SELECT * FROM tbl_judge_group WHERE Project_Id=?");
													$stmt->execute(array($row['Id']));
													$row_count = $stmt->rowCount();
													$xx=$xx+$xx*$row_count;
													echo $row_count; 
													?>
													</span>
													<i class="fa fa-users"></i> 
											Judge
													</button>
											 
										</td>
										<td>
										  <?php
										  /*
												$stmt1 = $db->prepare("SELECT * FROM tbl_viewer_sketch WHERE Project_Id=?");
													$stmt1->execute(array($row['Id']));
													$row_count1 = $stmt1->rowCount();
													$xx1=$row_count1;
												
												$stmt2 = $db->prepare("SELECT * FROM tbl_image_judge_sketch  WHERE Project_Id=?");
													$stmt2->execute(array($row['Id']));
													$row_count2 = $stmt2->rowCount();
													$xx2=$row_count2;
												$ttotal=$xx1+$xx2;
												if($xx != 0)
													$av=($ttotal*100)/$xx;
											echo $av;
											*/
											echo round(investment($ppid) * getAccount(), 2);
											?>
				
										</td>
										<td>
											
											<?php 
											
											echo $direction = Average_Rating($ppid);
											
											
											/*
											if ($row['Status'] == 0) { 
												echo '<button type="button" class="btn btn-default btn-xs"> Inactive</button>';
											} else if ($av<100) { 
												echo '<button type="button" class="btn btn-primary btn-xs">Active</button>';
											} else if ($av==100) {
												echo '<button type="button" class="btn btn-success btn-xs">Success</button>';
											} else { 
												echo '<button type="button" class="btn btn-danger btn-xs"> Failed</button>';
											 } 
											 
											 */?>
											
										</td>
										<td>
										
										<?php
										//var_dump($row['Judge_Direction']);
										 if( true){
												
												//echo "in true";	
												$direction = determain_direction($ppid); 	
												echo $direction;
												
												$database = new Config();
												$db = $database->getConnection();
												
												$project = new Project($db);
												
												$project->id = $ppid;
												$project->Judge_Direction = $direction;
												$project->status = 6;
												
												$q = $project->update_judge_direction();
												
												

											}else{
												//echo "in false";
												echo $row['Judge_Direction'];
											}
										 /*
										foreach ($db->query('SELECT count(Project_Id)as c, (AVG(Rate_Up*Confidence_Value_Up)) as avrg
										FROM tbl_image_judge_sketch  WHERE Project_Id='.$ppid.'') as $row) {
												   
												   // $stm11->execute(array($row['Id']));
												   $avgtt=$row['avrg'];
												   $meanConfidence=$avgtt;
												   
												   //echo $row['avrg'];
												   
										}*/
												
										?>
										</td>
										<td>
										<?php 
										
										$projectobj2 = new Project($db);
										
										$projectobj2->id = $ppid;
										
										$projectobj2->readOne();
										
											if($projectobj2->Online_Trade_Direction != NULL)
											{
												echo $projectobj2->Online_Trade_Direction;
											}
											else
											{
												$tradeDirection="Not Determined";
												echo $tradeDirection;
											}
										?>
										
										
										</td>
										<td>
											<?php
											if($projectobj2->status == 0){
												echo "Inactive";
											}else if($projectobj2->status == 1)
												echo "Active";
											else if($projectobj2->status == 3)
												echo "Image Viewing";
											else if($projectobj2->status == 4)
												echo "Image Judge";
											else if($projectobj2->status == 5)
												echo "Teading";
											else if($projectobj2->status == 6)
												echo "Completed";
											?>
										</td>
										<td>
											<div class="btn-group">
												<button type="button" class="btn btn-success">View</button>
												<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
													<span class="caret"></span>
													<span class="sr-only">Toggle Dropdown</span>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li><a href="task_clone.php?clone_id=<?php print($ppid); ?>">Clone</a></li>
													<li><a href="task_edit.php?edit_id=<?php print($ppid); ?>">Edit</a></li>
													<li><a data-id="<?php echo $ppid; ?>" href="javascript:void(0)">Delete</a></li>
												</ul>
											</div>
											<!--<a onclick="window.location.href='task_clone.php?edit_id=<?php //print($ppid); ?>'" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Clone </a>
											<a onclick="window.location.href='task_edit.php?edit_id=<?php //print($ppid); ?>'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
											<a class="delete_project btn btn-danger btn-xs" data-id="<?php //echo $ppid; ?>" href="javascript:void(0)"><i class="fa fa-trash-o"></i> Delete </a>-->
					
										</td>
									</tr>

								<?php } ?>
							</tbody>
						</table>
				

					</div>
				</div>
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
				

		<script src="bootstrap/js/bootbox.min.js"></script>
		<script>
			$(document).ready(function(){
				
				$('.delete_project').click(function(e){
					
					e.preventDefault();
					
					var pid = $(this).attr('data-id');
					var parent = $(this).parent("td").parent("tr");
					
					bootbox.dialog({
					  message: "Are you sure you want to Delete ?",
					  title: "<i class='glyphicon glyphicon-trash'></i> Delete Task !",
					  buttons: {
						success: {
						  label: "No",
						  className: "btn-success",
						  callback: function() {
							 $('.bootbox').modal('hide');
						  }
						},
						danger: {
						  label: "Delete!",
						  className: "btn-danger",
						  callback: function() {
							  
							  /*
							  
							  using $.ajax();
							  
							  $.ajax({
								  
								  type: 'POST',
								  url: 'delete.php',
								  data: 'delete='+pid
								  
							  })
							  .done(function(response){
								  
								  bootbox.alert(response);
								  parent.fadeOut('slow');
								  
							  })
							  .fail(function(){
								  
								  bootbox.alert('Something Went Wrog ....');
														  
							  })
							  */
							  
							  
							  $.post('task_delete', { 'delete':pid })
							  .done(function(response){
								  bootbox.alert(response);
								  parent.fadeOut('slow');
							  })
							  .fail(function(){
								  bootbox.alert('Something Went Wrog ....');
							  })
												  
						  }
						}
					  }
					});
					
					
				});
				
			});
		</script>

		</body>
		</html>
