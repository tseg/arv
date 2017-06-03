<?php
if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']))  
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
include_once  'crud/class/messaging.php';
include_once  'crud/class/user.php';
include_once  'crud/class/sketch_image.php';
 
 
$database = new Config();
$db = $database->getConnection();


$msg = new Messaging($db);


// set your default timezone
//date_default_timezone_set('Asia/Manila');
$timestamp = date('Y-m-d H:i:s');

if (isset($_POST['send'])) {

//var_dump($_POST);	
	
    //setting project variable
    //$reciver = htmlspecialchars($_POST['reciver']);
    $title = htmlspecialchars($_POST['title']);
    $Date_Time = $timestamp;
    $massage = htmlspecialchars($_POST['massage']);

 
	
    try {
        $db->beginTransaction();
		
		//$reciver = $_POST['reciver'];
		if(isset($_POST['reciver']) and count($reciver ) > 0){
        	for ($r = 0; $r < count($reciver); $r++) {
        	
        		$status = $msg->SendMessege($title ,$massage ,4 ,$reciver[$r] ,"Message" ,$Date_Time);
			}
		}else{
				$status = $msg->SendMessege($title ,$massage ,4 ,0 ,"Template" ,$Date_Time);
		}
		
        $db->commit();
   } catch (PDOException $ex) {
        //Something went wrong rollback!
       $db->rollBack();
       echo $ex->getMessage();
    }
	
}

if (isset($_POST['vsend'])) {
	
    //setting project variable
    //$reciver = htmlspecialchars($_POST['reciver']);
    $title = htmlspecialchars($_POST['title']);
    $Date_Time = $timestamp;
    $massage = htmlspecialchars($_POST['massage']);
	
	$project_image = new SketchJudgeImage($db);
	$project_image->projectid = htmlspecialchars($_POST['projectid']);
	
	$toviewer = $project_image->readviewer(); 
 
	//var_dump($toviewer);
	
	while ($vrow = $toviewer->fetch(PDO::FETCH_ASSOC)) {
		extract($vrow);
		//var_dump($vrow);
		//echo $vrow['VeiwerId'];

	
	
    try {
        $db->beginTransaction();
		
		//$reciver = $_POST['reciver'];
		//if(isset($vrow['VeiwerId']) and count($vrow) > 0){
        	//for ($r = 0; $r < count($reciver); $r++) {
        	
        $status = $msg->SendMessege($title ,$massage ,$_SESSION['user_session'] ,$vrow['VeiwerId'] ,"Message" ,$Date_Time);
			//}
		//}else{
		//		$status = $msg->SendMessege($title ,$massage ,4 ,0 ,"Template" ,$Date_Time);
		//}
		
        $db->commit();
   } catch (PDOException $ex) {
        //Something went wrong rollback!
       $db->rollBack();
       echo $ex->getMessage();
    }
   
   	}
	
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Compose Message</title>
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
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.min.css">

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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mailbox
        <small>13 new messages</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="mailbox.php" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="mailbox.php"><i class="fa fa-inbox"></i> Inbox
                  <!--<span class="label label-primary pull-right">12</span>--></a></li>
                <li><a href="sentedmail.php"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li><a href="mailtemplate.php"><i class="fa fa-file-text-o"></i> Template</a></li>
                <!--<li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a>
                </li>-->
                <li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box 
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Labels</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
           
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
              </ul>
            </div>
           
          </div>-->
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>
            </div>
            <!-- /.box-header -->
            <?php 
            if(isset($_GET['to']) && !empty($_GET['pid'])){
            ?>
            <form id="demo-form2" method="post" data-parsley-validate >
          	<?php 
          	//$user = new User($db);
          	//$stmt = $user->readAllUser();
			
			//$project_image = new SketchJudgeImage($db);
			//$project_image->projectid = $_GET['pid'];
			
			
			$project = new Project($db);
			
			$project->id =  htmlspecialchars($_GET['pid']);
			
			//read the project detail and assigne to project object attributes
			$project->readOne();
			
			//$toviewer = array();
			if($_GET['to'] == "viewer"){
				/*
				$toviewer = $project_image->readviewer(); 
						
                  $vrow = $toviewer->fetchAll(PDO::FETCH_ASSOC);
                  	   //var_dump($vrow);
                 for($i=0; $i < count($vrow); $i++){
					   		//echo $vrow[$i]['VeiwerId'];
                       $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
					   for($u = 0; $u < count($row); $u++){
                           //var_dump($row);
                           //echo $row[$u]['id']."&nbsp;".$vrow[$i]['VeiwerId']."<br>";
								//if($row[$u]['id'] == $vrow[$i]['VeiwerId'])
									//echo $row[$u]['first_name']."&nbsp;". $row[$u]['last_name'];	
						}
                             
				}*/
			
					   
			?>
            <div class="box-body">
            <br><p class="text-light-blue">Note: This is the preview of the task/email for Remote Viewer <cite title="Source Title"> (It can be cusstomized !)</cite></p>
              <div class="form-group">
              	<label class="control-label" for="title">Subject: <span class="required"></span>
                                                </label>
                <input name="title" value="Image Viewing Task" class="form-control" placeholder="Subject:" required />
              </div>
              <div class="form-group">
                    <textarea name="massage" id="compose-textarea" class="form-control" style="height: 300px">
					Hellow <br><br>
					
					<strong>You are assigned to new Remote veiwng Task !</strong><br>
					Please Describe and Sketch your feedback photo<br><br>
					
					Transaction is Due:<strong> <?php echo $project->Upload_Deadline; ?></strong><br><br>
					
					Thanks <br>
					Regards,<br>
					
					Admin
                    </textarea>
                    <input type="hidden" name="projectid" value="<?php echo $project->id; ?>" />
              </div>
              <!--<div class="form-group">
                <div class="btn btn-default btn-file">
                  <i class="fa fa-paperclip"></i> Attachment
                  <input type="file" name="attachment">
                </div>
                <p class="help-block">Max. 32MB</p>
              </div>-->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <!--<button name="draft" type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>-->
                <button name="vsend" type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              <!--<button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>-->
            </div>
            </form>
            <?php 
            }
            }else{
            ?>
            <form id="demo-form2" method="post" data-parsley-validate >
          	<?php 
          	$user = new User($db);
          	$stmt = $user->readAllUser();
			
			?>
            <div class="box-body">
              <div class="form-group">
              	 <select name="reciver[]" class="js-switch select2_multiple form-control" multiple="multiple">
              	 	
              	 	                               
                                                        <?php
                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            extract($row);
                                                            echo "<option value='{$id}'>{$first_name} &nbsp; {$last_name}</option>";
                                                        }
                                                        ?>  
                                                   
                                                       
                 </select>
                <!--<input name="reciver" class="form-control" placeholder="To:">-->
              </div>
              <div class="form-group">
                <input name="title" class="form-control" placeholder="Subject:">
              </div>
              <div class="form-group">
                    <textarea name="massage" id="compose-textarea" class="form-control" style="height: 300px">

                    </textarea>
              </div>
              <!--<div class="form-group">
                <div class="btn btn-default btn-file">
                  <i class="fa fa-paperclip"></i> Attachment
                  <input type="file" name="attachment">
                </div>
                <p class="help-block">Max. 32MB</p>
              </div>-->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <button name="draft" type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
                <button name="send" type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
            </div>
            </form>
            <?php
            }
            ?>
            <!-- /.box-footer -->
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
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.3.6
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
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
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Page Script -->
<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>







<!-- Select2 -->
<script src="dist/js/demo.js"></script>
<!-- select2 scripte -->
<link href="plugins/select2/select2.min.css" rel="stylesheet">
<script src="plugins/select2/select2.full.min.js"></script>

<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

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

</body>
</html>
