<?php
if(empty($_SESSION))
	session_start();
if(isset($_SESSION['role']) or $_SESSION['role'] != "Admin")  
{
    header("Location: index.php"); 
	exit;  
} 

include 'usercrud/class/config.php';
include 'usercrud/class/viewer.php';
include 'usercrud/class/judge.php';
include 'usercrud/class/crud.php';
include 'usercrud/class/project.php';
include 'setting.php';
 
$database = new Config();
$db = $database->getConnection();

//crude object creation
$crudobj = new Crud($db);

//project object creation 
$project = new Project($db);

//setting object creation 
$setting = new Setting($db);
//

//$date=new DateTime(); //this returns the current date time
//$result = $date->format('m/d/Y');


// set your default timezone
//date_default_timezone_set('Asia/Manila');
$timestamp = date('Y-m-d H:i:s');

if (isset($_POST['savestg'])) {
	
	var_dump($_POST);

    //setting project variable
    $project->Name = $_POST['projectname'];
    $project->Question = $_POST['question'];
    $project->Date_Time = $timestamp;
    $project->status = 1;

    //setting setting variables 
    $setting->Judge_Deadline = $_POST['judgetime'];
    $setting->Upload_Deadline = $_POST['uploadlast'];
    $setting->Place_Date_Time = $_POST['tradestart'];
    $setting->Exit_Date_Time = $_POST['tradeexit'];
    $setting->Notification_Time = $_POST['notiftime'];
    $setting->investment = $_POST['investment'];
    $setting->timestamp = $timestamp;

    //$li = $crudobj->createProjectSetting($_POST['judgetime'],$_POST['uploadlast'],$_POST['notiftime'],$_POST['tradeexit'],$_POST['tradestart'],100,$timestamp,2);

    function savesetting($db, $timestamp, $name, $question, $id) {
        $stmt = $db->prepare("INSERT INTO tbl_setting( Judge_Deadline,Upload_Deadline,Notification_Time,Exit_Date_Time,Place_Date_Time,Project_Id)"
                . " VALUES(:jdate,:udate,:ndate,:edate,:pdate,:invest)");
        $stmt->execute(array(':jdate' => $name, ':udate' => $question, ':ndate' => $timestamp, ':edate' => 'field4', ':pdate' => 'up', 'invest' => $id));
        return $stmt->rowCount();
    }

    function saveproject($db, $timestamp, $name, $question) {
        $stmt = $db->prepare("INSERT INTO tbl_project( Name,Question,Date_Time,Online_Trade_Direction,Revenue,Judge_Direction)"
                . " VALUES(:name,:question,:datetime,:tradedirection,:revenue,:judgedirection)");
        $stmt->execute(array(':name' => $name, ':question' => $question, ':datetime' => $timestamp, ':tradedirection' => 'field4', ':revenue' => 12.3, 'judgedirection' => 'up'));
        return $stmt->rowCount();
    }

    function getData($db) {
        $stmt = $db->query("SELECT * FROM tbl_setting join tbl_project on tbl_setting.Id = tbl_project.Id ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function deleteproject($db, $id) {

        $stmt = $db->prepare("DELETE FROM tbl_project WHERE Id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }

    function updateproject($db, $id, $name, $question) {
        $stmt = $db->prepare("UPDATE tbl_project SET Name = ?, Question = ? WHERE Id = ?");
        $stmt->execute(array($name, $question, $id));
        return $stmt->rowCount();
    }

    /*
      try {
      //var_dump(getData($db));
      //var_dump(savesetting($db, $timestamp,"insert test","is it"));
      //var_dump(deleteproject($db, 30));
      //var_dump(updateproject($db, 29, "ababa", "blabla"));
      var_dump(savesetting($db, "5/5/5","4/4/4","3/3/3",31));
      } catch (PDOException $ex) {
      echo "Error has happend";
      $ex->getMessage();
      } */

    try {
        $db->beginTransaction();
        
        $projectid = $project->create();
        
        $setting->projectId = $projectid;
        $setting->create();

        $projectviewers = $_POST['viewers'];
        //echo var_dump($projectviewers);
        for ($v = 0; $v < count($projectviewers); $v++) {
            //echo $projectviewers[$v];
            $crudobj->createViewerGroup($projectviewers[$v], $projectid, $timestamp);
        }

        $projectjudges = $_POST['judges'];
        for ($j = 0; $j < count($projectjudges); $j++) {
            $crudobj->createJudgeGroup($projectjudges[$j], $projectid, $timestamp);
        }
        
        for($isv = 0; $isv < count($projectviewers); $isv++){
            $projectid = $projectid;
            for($isj = 0; $isj < count($projectjudges); $isj++){ 
                $crudobj->createImageSketch($projectjudges[$isj],9,11,$timestamp,$projectid,$projectviewers[$isv],9);
            }
        }
        
        //$crudobj->createImageSketch(2,9,11,$timestamp,37,9,9);
        $db->commit();
    } catch (PDOException $ex) {
        //Something went wrong rollback!
        $db->rollBack();
        echo $ex->getMessage();
    }
}
?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Advanced form elements</title>
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

  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        AdminLTE Design Team
                        <small><i class="fa fa-clock-o"></i> 2 hours</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Developers
                        <small><i class="fa fa-clock-o"></i> Today</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Sales Department
                        <small><i class="fa fa-clock-o"></i> Yesterday</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Reviewers
                        <small><i class="fa fa-clock-o"></i> 2 days</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                      page and may cause design problems
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-red"></i> 5 new members joined
                    </a>
                  </li>

                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> You changed your username
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Create a nice theme
                        <small class="pull-right">40%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">40% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Some task I need to do
                        <small class="pull-right">60%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">60% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Make beautiful transitions
                        <small class="pull-right">80%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">80% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">Alexander Pierce</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="#" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">HEADER</li>
        <!-- Optionally, you can add icons to the links 
        <li class="active">-->
        <li class="treeview">
          <a href="#"><i class="fa fa-eye"></i> <span>Project Type (Protocol)</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Solo mode self judging</a></li>
            <li><a href="#">Solo mode dft judge</a></li>
            <li><a href="#">Group mode</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-archive"></i> <span>Profiles</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url().'Profile/index'; ?>"><i class="fa fa-magic"></i>Create New Profile</a></li>
            <li><a href="<?php echo base_url().'tbl_viewer/index'; ?>"><i class="fa fa-user"></i>Manage Viewers</a></li>
            <li><a href="<?php echo base_url().'tbl_judge/index'; ?>"><i class="fa fa-user-secret"></i>Manage Judge</a></li>
            <li><a href="<?php echo base_url().'Vgroup/index'; ?>"><i class="fa fa-users"></i>Manage Groups</a></li>
          </ul>
        </li>
        <li>
        	<a href="#">
        		<i class="fa fa-edit"></i><span>Link</span>
        	</a>
        </li>
        
        
        
        <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
    </section>

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
            	
            
             <h4 class="box-title">Task Basic Info</h4>	
            	
            	
<form id="demo-form2" method="post" data-parsley-validate class="form-horizontal form-label-left">

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Project Name <span class="required">*</span>
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
                                                    <div id="gender" class="btn-group" data-toggle="buttons">
                                                        <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                            <input type="radio" name="status" value="active"> &nbsp; Active &nbsp;
                                                        </label>
                                                        <label class="btn btn-primary active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                            <input type="radio" name="status" value="inactive" checked=""> Inactive
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                                                                        <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Investment (%) <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input id="investment" name="investment" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text">
                                                </div>
                                            </div>

                                            <?php
                                            $viewer = new Viewer($db);
                                            $judge = new Judge($db);

                                            $stmt = $viewer->readAllViewer();
                                            $viewernum = $stmt->rowCount();
                                            $jstmt = $judge->readAllJudge();
                                            ?>


                                            <h4>Project Member</h4>
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
<div class="row">
   <h4>Project Time Setting</h4>
   <div class="ln_solid"></div>
                                            
	<div class="col-md-6">
              <!-- Viewer Sketch Deadline Date -->
              <div class="form-group">
                <label>Viewer Sketch Deadline Date:</label>

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
    	              <!-- time Picker -->
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Time:</label>

                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                     <input type="text" class="form-control timepicker">
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
                     <input type="text" class="form-control timepicker">
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
                     <input type="text" class="form-control timepicker">
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
                     <input type="text" class="form-control timepicker">
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
                     <input type="text" class="form-control timepicker">
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
    </div>
</div>

          <div class="row">
            <div class="col-md-12">                               
              
                                     <div class = "ln_solid"></div>
                                            <div class = "form-group">
                                                <div class = "col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                    <button type = "submit" class = "btn btn-primary">Cancel</button>
                                                    <button name = "savestg" type = "submit" class = "btn btn-success">Submit</button>
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
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2016 <a href="http://almsaeedstudio.com">ARV Studio</a>.</strong> All rights
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
