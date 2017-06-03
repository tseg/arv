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

$user = new User($db);
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ARV | Register New User</title>
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
    </section> -->

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
			if (isset($_POST['btn-save'])) {
				$user->firstname =$_POST['first_name'];
				$user->lastname = $_POST['last_name'];
				$user->email = $_POST['email_id'];
				$user->contactno = $_POST['contact_no'];
				$user->userrole = $_POST['optionsRadios'];
				$user->username = $_POST['username'];
				$user->password = $_POST['password'];
				$user->status = 1;
			
				if ($user->create()){
				?>
			
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Alert!</strong> User is successfully created  </a>!
					</div>
			
				<?php
				}else{
				?>

					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Alert!</strong> ERROR while creating user !
					</div>
				<?php
				}
			}
			?>
	
			<form class="form-horizontal" method="POST">
				<div class="box-body">
				<div class="form-group">
					<label for="first_name" class="col-sm-2 control-label">First Name</label>
					<div class="col-sm-8">
						<input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required/>
					</div>
				</div>
				<div class="form-group">
					<label for="last_name" class="col-sm-2 control-label">Last Name</label>
					<div class="col-sm-8">
						<input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter Last Name" required/>
					</div>
				</div>
                <div class="form-group">
                  <label for="email_id" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-8">
                    <input type="email" name="email_id" class="form-control" id="inputEmail3" placeholder="Email" required/>
                  </div>
                </div>
                <div class="form-group">
                	<label for="contact_no" class="col-sm-2 control-label"> Phone Number</label>
                	<div class="col-sm-8">
                		<input type"text" name="contact_no" id="contact_no" class="form-control" placeholder="Phone Number" required />
                	</div>
                </div>
                <div class="form-group">
                	<label for="optionsRadios" class="col-sm-2 control-label"> User Type</label>
                	<div class="col-sm-8">
                		<input name="optionsRadios" id="optionsRadios1" value="Admin" checked="" type="radio">Admin 
						<input name="optionsRadios" id="optionsRadios2" value="Judge" type="radio">Judge 
						<input name="optionsRadios" id="optionsRadios3" value="Viewer" type="radio">Viewer 
                	</div>
                </div>
                <div class="form-group">
                	<label for="username" class="col-sm-2 control-label">User Name</label>
                	<div class="col-sm-8">
                		<input type='text' name='username' class='form-control' id="username" required>	
                	</div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">Password</label>

                  <div class="col-sm-8">
                    <input type="password" name="password" id="password" class="form-control" id="password" required />
                  </div>
					<!--<label>
						<input id="methods" type="checkbox"/>
							Show password
					</label>-->
                </div>
                <!--<div class="form-group">
                  <div class="col-sm-offset-2 col-sm-8">
                    <div class="checkbox">
                      <label>
                        <input id="methods" type="checkbox"> Show password
                      </label>
                    </div>
                  </div>
                </div>-->
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
              	<!--<button type="submit" class="btn btn-info "> Save </button>
                <button type="submit" class="btn btn-default"> Cancel </button>-->
                <button type="submit" class="btn btn-primary" name="btn-save">
					<span class="glyphicon glyphicon-plus"></span> Save Record
				</button>  
				<a href="user_mngt.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Cancel </a>
              </div>
			</form>
        	</div>
        </div>
      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2016 <a href="http://almsaeedstudio.com">ARV Studio</a>.</strong> All rights
    reserved.
  </footer>

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
<!-- show password -->
<script type="text/javascript" src="plugins/show-password/bootstrap-show-password.js"></script>

<!-- select2 scripte -->
<link href="plugins/select2/select2.min.css" rel="stylesheet">

<!-- Page script -->

<script>
    $(function () {
        $('#password').password().on('show.bs.password',function (e) {
            $('#methods').prop('checked', true);
        }).on('hide.bs.password', function (e) {
                    $('#methods').prop('checked', false);
                });
        $('#methods').click(function () {
            $('#password').password('toggle');
        });
    });


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
        <!-- show passwoord -->


</body>
</html>


