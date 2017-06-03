<?php
if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']) or $_SESSION['role'] != "Judge")  
{
    header("Location: index.php"); 
	exit;  
} 

error_reporting(~E_NOTICE); // avoid notice
require_once 'dbconfig.php';
include_once  'crud/class/config.php';
include_once  'crud/class/user.php';
include_once  'crud/class/sketch_image.php';
include_once  'crud/class/viewer_sketch.php';
include_once  'crud/class/image.php';

$database = new Config();
$db = $database->getConnection();

/*
$extensions = array('jpg','jpeg','gif','png','bmp');

$root = '';

$path = 'upload/';

function insertImage($db,$judge_id,$Sketch_ID,$Image_id_up,$Image_id_down,$timestamp,$prj){
	    $stmt = $db->prepare('INSERT INTO tbl_image_judge_sketch(Judge_Id,Sketch_Id,Image_Id_Up,Image_Id_Down,Date_Time,Project_Id) VALUES(:judge_id,:Sketch_ID,:Image_id_up,:Image_id_down,:timestamp,:prj)');
        $stmt->bindParam(':judge_id', $judge_id);
        $stmt->bindParam(':Sketch_ID', $Sketch_ID);
		
		$stmt->bindParam(':Image_id_up', $Image_id_up);
		$stmt->bindParam(':Image_id_down', $Image_id_down);
		$stmt->bindParam(':timestamp', $timestamp);
		$stmt->bindParam(':prj', $prj);

		
        if ($stmt->execute()) {
			return true;
			
        } else {
        	return false;

        }
}

function check_image($db, $jid, $sketchid, $pid){
		$query = "SELECT * FROM tbl_image_judge_sketch WHERE Judge_Id = ? AND Sketch_Id = ? AND Project_Id = ? LIMIT 0,1";
 		$stmt = $db->prepare($query);
       	$stmt->bindParam(1, $jid);
		$stmt->bindParam(2, $sketchid);
		$stmt->bindParam(3, $pid);
        $stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);					
			
		return $row;
}

function save_image_rating($db,$rateup,$confup,$ratedown,$confdown,$id)
{

        $query = "UPDATE
					tbl_image_judge_sketch
				SET
					Rate_Up = :rateup,
					Confidence_Value_Up = :confup,
					Rate_Down = :ratedown,
					Confidence_Value_Down = :confdown
				WHERE
					Id = :id";

        $stmt = $db->prepare($query);
		
		$stmt->bindParam(':rateup', $rateup);
		$stmt->bindParam(':confup', $confup);
		$stmt->bindParam(':ratedown', $ratedown);
		$stmt->bindParam(':confdown', $confdown);
		$stmt->bindParam(':id', $id);

        // execute the query
        if ($stmt->execute()) {
        	//echo "updated";
			echo '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Saved!</h4>
                The image rating is saved successfully !
              </div>';
			
            return true;
        } else {
        	echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Not Saved!</h4>
                Image rating is not saved! Please try again.
              </div>';
			  
            return false;
        }
}
 
function getImagesFromDir($path) {
    $images = array();
    if ( $img_dir = @opendir($path) ) {
        while ( false !== ($img_file = readdir($img_dir)) ) {
            // checks for gif, jpg, png
            if ( preg_match("/(\.gif|\.jpg|\.png|\.jpeg|\.bmp)$/", $img_file) ) {
                $images[] = $img_file;
            }
        }
        closedir($img_dir);
    }
    return $images;
}

function getRandomFromArray($ar) {
    mt_srand( (double)microtime() * 1000000 ); // php 4.2+ not needed
    $num = array_rand($ar);
    return $ar[$num];
}

// Obtain list of images from directory 
$imgList = getImagesFromDir($root . $path);
	
if(!empty($imgList)) // Do we have something to show?
{

	//$img = getRandomFromArray($imgList);

	$rand_key = array_rand($imgList, 1);

	$srcup = $imgList[$rand_key];

	//echo "<img src='upload/".$srcup."' align='absmiddle'>";

	unset($imgList[$rand_key]);

	$rand_key = array_rand($imgList, 1);

	$srcdown = $imgList[$rand_key];

	//echo "<br /><img src='upload/".$srcdown."' align='absmiddle'>";
}
else
{
	echo 'No images were found in <strong>'.$path.'</strong>';
}


*/
						
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ARV | Task Rate</title>
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
          <h3 class="box-title">Image Rating</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
            	<div style="text-align:center">
            		<?php
            			
						if(isset($_POST['btnsave'])){
							//var_dump($_POST);
							//image judge sketch class Object
							$ijsOBJ = new SketchJudgeImage($db);
							
							$ijsOBJ->rateup = htmlspecialchars($_POST['reateup']);
							$ijsOBJ->confup = htmlspecialchars($_POST['confup']);
							$ijsOBJ->ratedown = htmlspecialchars($_POST['reatedown']);
							$ijsOBJ->confdown = htmlspecialchars($_POST['confdown']);
							$ijsOBJ->id = htmlspecialchars($_POST['recoredid']);

							if ($ijsOBJ->save_image_rating()) {
								echo '<div class="alert alert-success alert-dismissible">
                				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                				<h4><i class="icon fa fa-check"></i> Saved!</h4>
                				The image rating is saved successfully !
              					</div>';
        					} else {
        						echo '<div class="alert alert-danger alert-dismissible">
                				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                				<h4><i class="icon fa fa-ban"></i> Not Saved!</h4>
                				Image rating is not saved! Please try again.
              					</div>';
        					}
	
						}
            		
            		
            			if (isset($_GET['sketch_id']) && !empty($_GET['sketch_id'])) {

							$viewersketch = new Viewersketch($db);
							
							$projectimages = new SketchJudgeImage($db);
							
							$sketchid = $_GET['sketch_id'];
							
							$viewersketch->id = $sketchid;
							
							$stmt = $viewersketch->readone();
							
							if ($stmt->rowCount() > 0) {
								$row = $stmt->fetch(PDO::FETCH_ASSOC);
								
								//var_dump($row);
								$judge_id = $_SESSION['user_session'];
								
								//date_default_timezone_set('Asia/Manila');
								$timestamp = date('Y-m-d H:i:s');
								
								$projectimages->judgeid = $judge_id;
								$projectimages->sketchid = $sketchid;
								$projectimages->projectid = $row['Project_Id'];
								$projectimages->veiwerid = $row['Viewer_ID'];
								
								//$cstmt = $projectimages->readimages();
								$cstmt = $projectimages->check_image();
								
								if($cstmt->rowCount() > 0){
								$crow = $cstmt->fetch(PDO::FETCH_ASSOC);	
								
								//var_dump($crow);
								$imageobj = new Image($db);
										
								$imageobj->id = $crow['Image_Id_Up'];
								$istmt1 = $imageobj->readone();
								$irow1 = $istmt1->fetch(PDO::FETCH_ASSOC);
								$srcup = $irow1['Image_Path'];
										
								$imageobj->id = $crow['Image_Id_Down'];
								$istmt2 = $imageobj->readone();
								$irow2 = $istmt2->fetch(PDO::FETCH_ASSOC);
								$srcdown = $irow2['Image_Path'];
								
								//var_dump($srcdown);
								//var_dump($srcup);
							
            		?>
            		<p class="page-header"><?php echo $row['Caption']; ?></p>
                	<img src="viewer_upload/<?php echo $row['Image_Path'];?>" class="img-rounded" width="200px" height="200px" />	
                	<!--<input type='text' name="tblid" value="<?php echo $tblrowid; ?>" class="form-control"/>-->
            	</div>
        	</div>

        	<div class="row">


			<form role="form" method="post">	
			<div class="col-xs-12">
                <input type="hidden" name="recoredid" id="recoredid" required="required" value="<?php echo $crow['Id']; ?>" class="form-control col-md-7 col-xs-12">
            </div>
            <div class="col-xs-6">
                <p class="page-header">Randome Image One</p>
                <img src="upload/<?php echo $crow['Image_Id_Up']; ?>" class="img-rounded" width="200px" height="200px" />
				<div class="col-lg-4">	
						
					<tr>
                                        <td>Rate</td>
                                        <td><input type='number' name="reateup" id="rate" required="required" min="1.0" max="5.0" class="form-control"></td>
										<td>Confidence Value</td>
                                        <td><input type='number' name="confup" required="required" id="confidence" min="1" max="10" class="form-control"></td>
										<!-- To hold the current Id of image being viewed-->

                                        
                    </tr>	
				</div>
			    
            </div>  
            <div class="col-xs-6">
                <p class="page-header">Randome Image Two</p>
                <img src="upload/<?php echo $crow['Image_Id_Down'];?>" class="img-rounded" width="200px" height="200px" />
				<div class="col-lg-4">				
					<tr>
                                        <td>Rate</td>
                                        <td><input type='number' name="reatedown" id="rate" required="required"min="1.0" max="5.0" class="form-control"></td>
										<td>Confidence Value</td>
                                        <td><input type='number' name="confdown" required="required" id="confidence" min="1" max="10" class="form-control"></td>
										<!-- To hold the current Id of image being viewed-->                                        
                                               
                    </tr>	
				</div>
			    
            </div>     
</div>
<!--<a class="btn btn-info" href="edit_image.php?edit_id=<?php echo $row['Id']; ?>" title="click for edit" onclick="return confirm('sure to edit ?')">Save</a> -->
						<div style="text-align:center">
 						 <fieldset>
 						 	
							<button type="submit" name="btnsave" class="btn btn-default"><span class="glyphicon glyphicon-save"></span> &nbsp; Save </button>
						</fieldset>
						</div>
          </form>	
        </div>
      </div>
      <!-- /.box -->
	
<?php
		}
	}
}

?>

      
  


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
