<?php
//require_once 'dbconfig.php';

if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']) or $_SESSION['role'] != "Admin")  
{
    header("Location: index.php"); 
	exit;  
}

error_reporting(~E_NOTICE); // avoid notice

include_once  'dbconfig.php';
include_once  'crud/class/config.php';
include_once  'crud/class/viewer.php';
include_once  'crud/class/judge.php';
include_once  'crud/class/image.php';
include_once  'crud/class/project.php';
include_once  'crud/class/setting.php';
include_once  'crud/class/viewergroup.php';
include_once  'crud/class/judgegroup.php';
include_once  'crud/class/practice.php';

$database = new Config();
$db = $database->getConnection();

if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt_edit = $DB_con->prepare('SELECT * FROM tbl_image WHERE Id =:id');
    $stmt_edit->execute(array(':id' => $id));
    $edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
    extract($edit_row);
} //else {
   // header("Location: index.php");
//}


if (isset($_POST['btnsave'])) {
	
	
    $caption = $_POST['Caption']; 
    $description = $_POST['Description']; 

    $imgFile = $_FILES['Image']['name'];
    $tmp_dir = $_FILES['Image']['tmp_name'];
    $imgSize = $_FILES['Image']['size'];
	
	$caption2 = $_POST['Caption2']; 
    $description2 = $_POST['Description2']; 

    $imgFile2 = $_FILES['Image2']['name'];
    $tmp_dir2 = $_FILES['Image2']['tmp_name'];
    $imgSize2 = $_FILES['Image2']['size'];


    if (empty($caption) or empty($caption2)) {
        $errMSG = "Please enter caption for your image.";
    } else if (empty($description) or empty($description2)) {
        $errMSG = "Please enter description.";
    } else if (empty($imgFile) or empty($imgFile2)) {
        $errMSG = "Please select image file.";
    } else {
        $upload_dir = 'upload/'; // upload directory

        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); 
        $imgExt2 = strtolower(pathinfo($imgFile2, PATHINFO_EXTENSION));
        
        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
        // rename uploading image
        $pic_path = rand(1000, 1000000) . "." . $imgExt;
		$pic_path2 = rand(1000, 1000000) . "." . $imgExt2;

        // allow valid image file formats
        if (in_array($imgExt, $valid_extensions) or in_array($imgExt2, $valid_extensions)) {
            // Check file size '5MB'
            if ($imgSize < 5000000 and $imgSize2 < 5000000) {
                move_uploaded_file($tmp_dir, $upload_dir . $pic_path);
				move_uploaded_file($tmp_dir2, $upload_dir . $pic_path2);
            } else {
                $errMSG = "Sorry, your files are too large.";
            }
        } else {
            $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }


    // if no error occured, continue ....
    if (!isset($errMSG)) {
    	/*
        $stmt = $DB_con->prepare('INSERT INTO tbl_image(Caption, Description, Image_Path) VALUES(:caption, :description, :image_Path)');
        $stmt->bindParam(':caption', $caption);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_Path', $pic_path);
		*/
		try{
			
			
			$imageOBJ = new Image($db);
			
			$imageOBJ->caption = $caption;
			$imageOBJ->description = $description;
			$imageOBJ->filename = $pic_path;
			$imageOBJ->caption2 = $caption2;
			$imageOBJ->description2 = $description2;
			$imageOBJ->filename2 = $pic_path2;
			
        	if ($imageOBJ->create2()) {
            	$successMSG = "New image is uploaded succesfully ...";
            //header("refresh:5;index.php"); // redirects image view page after 5 seconds.
        	} else {
           		$errMSG = "Error while Inserting the image data into Database ....";
        	}
		}catch(PDOException $ex){
			
			print_r($ex->getMessage());
		}
    }
}
//include_once 'header_viewer.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ARV | Add New Images</title>
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
        
        
        <form method="post" enctype="multipart/form-data">
        <div class="box-body">
		<div class="row">
			 <div class="col-md-12">
            	<?php 
            		if (isset($successMSG)){
				?>
			
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong> <?php echo $successMSG; ?> !</strong>
					</div>
			
				<?php
				}
				
				if(isset($errMSG)){
				?>

					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong> <?php echo $errMSG; ?>  !</strong>
					</div>
				<?php
				}
            	?>
           	</div>
		</div>
		
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
										<label class="control-label">Caption </label>
										<input class="form-control" type="text" name="Caption" placeholder="Enter Image Caption" value="" />
									</div>
									<div class="form-group">
										<label class="control-label">Description </label>
                                    	<input class="form-control" type="text" name="Description" placeholder="Your Description" value="" />
									</div>
									<div class="form-group">
										<label class="control-label">Image </label>
                                    	<input class="input-group" type="file" name="Image" accept="image/*" />
									</div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
										<label class="control-label">Caption Two</label>
                                    	<input class="form-control" type="text" name="Caption2" placeholder="Enter Image Caption two" value="" />
									</div>
									<div class="form-group">
										<label class="control-label">Description Two</label>
                                    			<input class="form-control" type="text" name="Description2" placeholder="Your Description two" value="" />
									</div>
									<div class="form-group">
										<label class="control-label">Image Two </label>
                                    	<input class="input-group" type="file" name="Image2" accept="image/*" />
									</div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
                <div class="box-footer">
                
        			<div class="col-xs-12">
          				<button type="submit" class="btn btn-primary" name="btnsave">
											<span class="glyphicon glyphicon-plus"></span> Save Images
										</button>  
										<a href="image_gallery.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Cancel </a>
                                    
        		</div>
      		</div>
        </div>
        <!-- /.box-body -->
		</form>
        
        
        
        
        
        


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


