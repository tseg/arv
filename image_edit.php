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

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ARV | Image Edit</title>
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
          <h3 class="box-title">Image Upload</h3>

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
if (isset($_POST['btn_save_updates'])) {

	$imageOBJ = new Image($db);
	
	$imageOBJ->id = $_POST['id'];
    $imageOBJ->caption = $_POST['caption'];
    $imageOBJ->description = $_POST['description'];
	
	$imageOBJ->caption2 = $_POST['caption2'];
    $imageOBJ->description2 = $_POST['description2'];

    $imgFile = $_FILES['edit_image']['name'];
    $tmp_dir = $_FILES['edit_image']['tmp_name'];
    $imgSize = $_FILES['edit_image']['size'];
    
	$imgFile2 = $_FILES['edit_image2']['name'];
    $tmp_dir2 = $_FILES['edit_image2']['tmp_name'];
    $imgSize2 = $_FILES['edit_image2']['size'];
	
    if ($imgFile) {
    		
    	$stmt = $imageOBJ->readone();
		$edit_row = $stmt->fetch(PDO::FETCH_ASSOC);
    	extract($edit_row);
		

        $upload_dir = 'upload/'; 
			
        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); 
		$imgExt2 = strtolower(pathinfo($imgFile2, PATHINFO_EXTENSION)); 
		
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
        
        
        $pic_name = rand(1000, 1000000) . "." . $imgExt;
		$pic_name2 = rand(1000, 1000000) . "." . $imgExt2;
		
		$imageOBJ->filename = $pic_name;
		$imageOBJ->filename2 = $pic_name2;
		
        if (in_array($imgExt, $valid_extensions) and in_array($imgExt2, $valid_extensions)) {
            if ($imgSize < 5000000 and $imgSize2 < 5000000) {
                //unlink($upload_dir . $edit_row['Image_Path']); // delete the previouse image
                move_uploaded_file($tmp_dir, $upload_dir . $pic_name);
				move_uploaded_file($tmp_dir2, $upload_dir . $pic_name2);
				
            } else {
                $errMSG = "Sorry, your file is too large it should be less then 5MB";
            }
        } else {
            $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }else {

        // if no image selected the old image remain as it is.
        $stmt = $imageOBJ->readone();
		$edit_row = $stmt->fetch(PDO::FETCH_ASSOC);
    	extract($edit_row);
		
        $imageOBJ->filename = $edit_row['Image_Path']; 
        $imageOBJ->filename2 = $edit_row['Image_Path2'];
    }

    if (!isset($errMSG)) {
   		if($imageOBJ->update2()){ 			
?>
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong> Successfully Updated ... ! </strong>
					</div>
<?php
        } else {
?>
            		<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong> Sorry The Image Could Not Updated !</strong>
					</div>
<?php
        }
		 
    }else{
?>
    	           <div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong> <?php echo $errMSG; ?> </strong>
					</div>
<?php
    }
}

?>
            	
            	<?php 
            	if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
						
						$imageOBJ = new Image($db);
						
						$imageOBJ->id = $_GET['edit_id'];
						$stmt = $imageOBJ->readone();
						
    					//$stmt_edit = $DB_con->prepare('SELECT * FROM tbl_image WHERE Id =:id');
    					//$stmt_edit->execute(array(':id' => $id));
    					
   						$edit_row = $stmt->fetch(PDO::FETCH_ASSOC);
    					extract($edit_row);
            			?>
 							<form method="post" enctype="multipart/form-data" >
                            
                            <div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Caption 1</label>
													<input class="form-control" type="text" name="caption" value="<?php echo $edit_row["Caption"]; ?>" required />
												</div>
												<div class="form-group">
													<label class="control-label">Description 1</label>
													<input class="form-control" type="text" name="description" value="<?php echo $edit_row["Description"]; ?>" />
												</div>
												<div class="form-group">
													<label class="control-label">Previous Img.</label>
													<p><img src="upload/<?php echo $edit_row["Image_Path"]; ?>" height="150" width="150" />
													</p>
													<input class="input-group" type="file" name="edit_image" accept="image/*" />
												</div>
												<!-- /.form-group -->
											</div>
											<!-- /.col -->
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Caption 2</label>
													<input class="form-control" type="text" name="caption2" value="<?php echo $edit_row["Caption2"]; ?>" required />
												</div>
												<div class="form-group">
													<label class="control-label">Description 2</label>
													<input class="form-control" type="text" name="description2" value="<?php echo $edit_row["Description2"]; ?>" />
												</div>
												<div class="form-group">
													<label class="control-label">Previous Img.</label>
													<p><img src="upload/<?php echo $edit_row["Image_Path2"]; ?>" height="150" width="150" />
													</p>
													<input class="input-group" type="file" name="edit_image2" accept="image/*" />
													<!--<<td><label class="control-label">Image </label></td> -->
												</div>
												<!-- /.form-group -->
											</div>
											<!-- /.col -->
										</div>
										<!-- /.row -->
										<div class="box-footer">

											<div class="col-xs-12">
												<input type="hidden" name="id" value="<?php echo $edit_row["Id"]; ?>" />
												<button type="submit" name="btn_save_updates" class="btn btn-primary">
													<span class="glyphicon glyphicon-save"></span> Update
												</button>
												<a class="btn btn-large btn-success" href="image_gallery.php"> <span class="glyphicon glyphicon-backward"></span> cancel </a>

											</div>
										</div>

										</form>
                        
                        <?php 
                        }else{
    						header("Location: image_gallery.php");
						}
              ?>
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
