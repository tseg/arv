<?php
if(empty($_SESSION))
	session_start();
if(!isset($_SESSION['role']))  
{
    header("Location: index.php"); 
	exit;  
}
require_once 'dbconfig.php';
include_once  'crud/class/config.php';

$database = new Config();
$db = $database->getConnection();
 
?>
<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>ARV</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>ARV </b> Experiment</span>
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
          
          <!-- Notifications: style can be found in dropdown.less -->

          <!-- Tasks: style can be found in dropdown.less -->
          
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">
              			<?php
							foreach ($db->query('SELECT * FROM tbl_users where id='.$_SESSION['user_session'].'') as $row) {
								echo $row['first_name']."   ".$row['last_name'];
							}			 
						?>	
              
              </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  		<?php
							foreach ($db->query('SELECT * FROM tbl_users where id='.$_SESSION['user_session'].'') as $row) {
								echo $row['first_name']."   &nbsp;".$row['last_name']."<br>";
								echo "<small>".$row['user_role']."</small>";
							}
										 
						?> 
                 
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php?logout=true" class="btn btn-default btn-flat">Sign out</a>
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