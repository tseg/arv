<?php
if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']) or $_SESSION['role'] != "Viewer")  
{
    header("Location: index.php"); 
	exit;  
} 
include_once 'crud/class/config.php';
include_once 'crud/class/project.php';
include_once  'crud/class/messaging.php';
include_once  'crud/class/user.php';
include_once  'crud/class/practice.php';


$database = new Config();
$db = $database->getConnection();

$projectobj = New Project($db);

//var_dump(getData($db));

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ARV | Practice Report</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.print.css" media="print">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
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
        Mailbox
        <small>13 new messages</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Practices</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Search Mail">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">

              <div class="table-responsive">
                <table class="table table-hover table-striped">
                <thead><td>#</td><td>Practice Name</td><td>Created Date</td><td>Status</td><td>Action</td>
                </thead>
                  <tbody>
                  	
                  <?php
                  $practice = new Practice($db);
				  
          		  //$msgstmt = $msg->GetByReceiverID($_SESSION['user_session']);
				  
                  $practicestmt = $practice->readAllPractice($_SESSION['user_session']);
				  
					$i = 1;
                       while ($row = $practicestmt->fetch(PDO::FETCH_ASSOC)) {
                       		extract($row);

                       		//echo "<option value='{$id}'>{$first_name} &nbsp; {$last_name}</option>";
							
							    echo "<tr>";
							    echo "<td> {$i} </td>";
								echo "<td class='mailbox-name'>";
								                   			
								 if(empty($Practice_Name)){
								 	echo "ARV Practice without Lable";
								 }else{
								 	echo "{$Practice_Name}";	
								 }
								 
								 /*
								$user = new User($db);
          		  				$userstmt = $user->readAllUser();
								 while ($urow = $userstmt->fetch(PDO::FETCH_ASSOC)) {
                                      extract($urow);
                                      if($urow['id'] == $row['sender'])
                                      echo "{$first_name} &nbsp; {$last_name} &nbsp; ({$user_role})";
                                 }
                    			*/
                    			echo "</td>";
                    			echo "<td class='mailbox-attachment'> {$Date_Time}</td>";
                    			if($row['Status'] == 0)
									echo "<td class='mailbox-date'> Started </td>";
								elseif($row['Status'] == 1)
									echo "<td class='mailbox-date'> Viewing </td>";
								elseif($row['Status'] == 2)
									echo "<td class='mailbox-date'> Feedback </td>";
								elseif($row['Status'] == 3)
									echo "<td class='mailbox-date'> Completed </td>";
									
								
								echo "<td class='mailbox-name'>";
								if($row['Status'] == 1 or $row['Status'] == 0)
                    				echo "<a href=practice_image.php?pid=".$row['id'].">Procssed </a>";
								if($row['Status'] == 2)
									echo "<a href=practice_feedback.php?pid=".$row['id'].">Procssed </a>";
								if($row['Status'] == 3)
									echo "Complete";

                    			echo "</td>";
                  				echo "</tr>";
								$i = $i + 1;
                       }
                  ?> 
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <div class="pull-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
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
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- Page Script -->
<script>
  $(function () {
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");

      //Switch states
      if (glyph) {
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }

      if (fa) {
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });
  });
</script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
