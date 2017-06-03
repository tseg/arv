<?php
if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']) or $_SESSION['role'] != "Admin")  
{
    header("Location: index.php"); 
	exit;  
} 

include_once 'crud/class/config.php';
$database = new Config();
$db = $database->getConnection();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ARV | Image Gallery</title>
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
                                                $stmt = $db->prepare('SELECT * FROM tbl_image ORDER BY Id DESC');
                                                $stmt->execute();
												
												//$resultset = $stmt->fetchALL(PDO::FETCH_ASSOC);
												//$name = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
												
												//var_dump($name);
												//var_dump($resultset);
												?>
												
												<?php
                                                if ($stmt->rowCount() > 0) {
                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                                        extract($row);
														
                                                        ?>
                                                        
                                                        
                                                        <div class="col-sm-6">
                                                        	<div class="thumbnail">
                      										<div class="row">
                       											<div class="col-sm-6">
                          											<div class="thumbnail">
                                                                <div class="image view view-first">
                                                                    <img class="img-responsive" src="upload/<?php echo $row['Image_Path']; ?>" alt="image" />
                                                                    <div>
                                                                        <strong><?php echo $row['Caption'] ;//. "&nbsp;/" . $row['Category']; ?></strong>
 																		<div >
                                                                    		<small><?php echo $row['Description']; ?></small>
                                                                		</div>
                                                                    </div>
                                                                </div>
                                                                </div>
                          											
                          											
                        										</div>
                        									<!-- /.col -->
                        										<div class="col-sm-6">
                          											 <div class="thumbnail">
                                                               			<div class="image view view-first">
                                                                    		<img class="img-responsive" block;" src="upload/<?php echo $row['Image_Path2']; ?>" alt="image2" />                                                                    		
                                                                    		<div>
                                                                        		<strong><?php echo $row['Caption2'] ;//. "&nbsp;/" . $row['Category']; ?></strong>
 																				<div >
                                                                    				<small><?php echo $row['Description2']; ?></small>
                                                                				</div>
                                                                    		</div>
                                                                		</div>
                                                                	</div>
                        										</div>						
                       
                     										</div>
                     										<div class="box-footer">
              													<a href="image_edit.php?edit_id=<?php echo $row['Id']; ?>" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-pencil"></i> &nbsp; Edit Images </a>
              													<!--<a href="image_delete.php?delete_id=<?php echo $row['Id']; ?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i> &nbsp; Delete Images </a>-->
                      											<a class="delete_image btn btn-danger btn-sm" data-id="<?php echo $row['Id']; ?>" href="javascript:void(0)"><i class="fa fa-trash-o"></i> &nbsp; Delete Images</a>
                      										</div>
                      										</div>
                    									</div>

                                                        
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="col-xs-12">
                                                        <div class="alert alert-warning">
                                                            <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Data Found ...
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                
        	</div>
        </div>
      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper 
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2016 <a href="http://almsaeedstudio.com">ARV Studio</a>.</strong> All rights
    reserved.
  </footer>-->

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

<!-- delete box -->
<script src="bootstrap/js/bootbox.min.js"></script>

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
        

		<script>
			$(document).ready(function(){
				
				$('.delete_image').click(function(e){
					
					e.preventDefault();
					
					var pid = $(this).attr('data-id');
					var parent = $(this).parent("td").parent("tr");
					
					bootbox.dialog({
					  message: "Are you sure you want to delete Images ?",
					  title: "<i class='glyphicon glyphicon-trash'></i> Delete Images !",
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
							  
							  
							  $.post('image_delete', { 'delete_id':pid })
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

