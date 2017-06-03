<?php
if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']) or $_SESSION['role'] != "Viewer")  
{
    header("Location: index.php"); 
	exit;  
} 

?>
<meta http-equiv="refresh" content="1200;url=index.php" />
<?php
//include_once 'class.imagecrud.php';
include_once 'crud/class/config.php';
include_once 'crud/class/project.php';
include_once 'crud/class/sketch_image.php';


$database = new Config();
$db = $database->getConnection();


$project = new Project($db);
$Viewer_Id = $_SESSION['user_session'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ARV| Viewers Task Page</title>
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
		<?php 
		$image_judge_sketch_obj = new SketchJudgeImage($db);
		$image_judge_sketch_obj->veiwerid = $Viewer_Id;
		$ijs_stmt = $image_judge_sketch_obj->read_project_viewer_join();
		
		$upload_image = 0;
		$expire_image = 0;
		$complet_image = 0;
		$total_image = 0;
		$success_task = 0;
		$faild_task = 0;
		
		$cdatetime = date('Y-m-d H:i:s');
		if($ijs_stmt->rowCount() > 0)
  		{

  		 	while($row = $ijs_stmt->fetch(PDO::FETCH_ASSOC)){
  		 		
				if($row['VeiwerId'] == $Viewer_Id){
					$img_upload_date = $row['Upload_Deadline'];
					
					$date=new DateTime(); 
					$current_date = $date->format('m/d/Y');
						
				
					if($row['Sketch_Id'] == null and $img_upload_date >= $current_date){
						$upload_image = $upload_image + 1;
					}elseif($row['Sketch_Id'] == null and $img_upload_date <= $current_date){
						$expire_image = $expire_image + 1;
					}else if($row['Sketch_Id'] != null and $img_upload_date <= $current_date){
						$complet_image = $complet_image + 1;
				
						if($row['Judge_Direction'] != null and $row['Online_Trade_Direction'] != null){
							if($row['Judge_Direction'] == $row['Online_Trade_Direction'])
								$success_task = $success_task + 1;
							elseif($row['Judge_Direction'] != $row['Online_Trade_Direction'])
								$faild_task = $faild_task + 1;	
						}

					}else if($row['Sketch_Id'] != null and $img_upload_date >= $current_date){
						$complet_image = $complet_image + 1;
					}
				
					$total_image = $total_image + 1;
				}
			}
		}
		
		?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $upload_image; ?></h3>

              <p>New ARV Tasks</p>
            </div>
            <a href="viewer_task_page.php" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $complet_image; ?></h3>

              <p>Completed ARV Tasks</p>
            </div>
            <a href="viewer_task_feedback_page.php" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $expire_image; ?></h3>

              <p>Expired ARV Tasks</p>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $success_task; ?>/<?php echo $total_image ?></h3>

              <p>Successfull ARV Tasks</p>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

	
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Viewer Tasks Table</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">

            			<div class="table-responsive">
            		       		<table class="table no-margin table-bordered table-responsive" id="datatable">
                                    <thead>
                                            <tr>	
                                            <th> # </th>									
											<th> Project Name </th>
											<th> Upload_Deadline </th>
											<th> Status </th>
                                            <th> Actions </th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        <?php
        $image_judge_sketch_obj = new SketchJudgeImage($db);
		$image_judge_sketch_obj->veiwerid = $Viewer_Id;
		$ijs_stmt = $image_judge_sketch_obj->read_project_viewer_join();
																				
		if($ijs_stmt->rowCount() > 0)
  		{

  		 	while($row = $ijs_stmt->fetch(PDO::FETCH_ASSOC)){
  		 		
				if($row['VeiwerId'] == $Viewer_Id){
					
					echo "<tr>";
					echo "<td> Tsk".$row['Id']."</td>";
                	echo "<td>".$row['Name']."</td>";
					echo "<td>".$row['Upload_Deadline']."</td>";
					
					
					$img_upload_date = $row['Upload_Deadline'];
					
					$date=new DateTime(); 
					$current_date = $date->format('m/d/Y');
					
					if($row['Sketch_Id'] == null and $img_upload_date >= $current_date){
						echo "<td><span class='label label-info'>Uploading in Progress</span></td>";
					}elseif($row['Sketch_Id'] == null and $img_upload_date <= $current_date){
						echo "<td><span class='label label-danger'>Uploading Expired</span> </td>";
					}else if($row['Sketch_Id'] != null and $img_upload_date <= $current_date){
						echo "<td><span class='label label-success'>Uploading Completed</span></td>";
					}else if($row['Sketch_Id'] != null and $img_upload_date >= $current_date){
						echo "<td><span class='label label-success'>Uploading Completed</span></td>";
					}
					
					if($row['Sketch_Id'] == null and $img_upload_date >= $current_date){
						echo "<td> <a href='viewer_sketch_upload.php?task_id=". $row['Project_Id']."' class='btn btn-block btn-primary'>Upload Sketch</a> </td>";
					}else{
						echo "<td> <a href='viewer_sketch_upload.php?task_id=". $row['Project_Id']."' class='btn btn-block btn-default disabled'>Upload Sketch</a> </td>";
					}
				}
			}
		}else{
			echo "<tr><td>Nothing here...</td></tr>";			
		}
		
                                        ?>
                                    </tbody>
                                </table>
                </div>
			
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
</body>
</html>
