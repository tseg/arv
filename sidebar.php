<?php 
if(empty($_SESSION))
	session_start();
if(!$_SESSION['role'])  
{
    header("Location: index.php"); 
	exit;  
} 

?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          
          				<?php
							foreach ($db->query('SELECT * FROM tbl_users where id='.$_SESSION['user_session'].'') as $row) {
								echo "<p>".$row['first_name'];echo "   ";echo $row['last_name']."</p>";
								echo "<a href='#'><i class='fa fa-circle text-success'></i>".$row['user_role']."</a>";
							}
										 
						?>
          <!--<p></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>
      <!-- search form 
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php
		foreach ($db->query('SELECT * FROM tbl_users where id='.$_SESSION['user_session'].'') as $row) {
		if($row['user_role'] == "Admin"){
	 ?>
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Manage Task</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="task_create.php"><i class="fa fa-circle-o"></i> Create New Task</a></li>
            <li><a href="tasks.php"><i class="fa fa-circle-o"></i> View All Tasks</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-image"></i> <span>Manage Image</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="image_add.php"><i class="fa fa-circle-o"></i> Uploade New Image</a></li>
            <li><a href="image_gallery.php"><i class="fa fa-circle-o"></i> Manage All Images</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>Manage Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="user_add.php"><i class="fa fa-circle-o"></i> Register New User</a></li>
            <li><a href="user_mngt.php"><i class="fa fa-circle-o"></i> Manage All Users</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="mailbox.html">
            <i class="fa fa-envelope"></i> <span>Meassages</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="mailbox.php">Inbox
                <span class="pull-right-container">
                  <!--<span class="label label-primary pull-right">13</span>-->
                </span>
              </a>
            </li>
            <li><a href="compose.php">Compose</a></li>
            <!--<li class="active"><a href="read-mail.html">Read</a></li>-->
          </ul>
        </li>
      </ul>
      <?php
      		}elseif($row['user_role'] == "Judge"){
 	?>
 	      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Manage Task</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="judge_task_page.php"><i class="fa fa-circle-o"></i> Start Task Judging</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i>Judging Report</a></li>
          </ul>
        </li>
        <!--
        <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>Manage Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="user_add.php"><i class="fa fa-circle-o"></i> Register New User</a></li>
            <li><a href="user_mngt.php"><i class="fa fa-circle-o"></i> Manage All Users</a></li>
          </ul>
        </li>-->
        <li class="treeview">
          <a href="mailbox.html">
            <i class="fa fa-envelope"></i> <span>Meassages</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="mailbox.php">Inbox
                <span class="pull-right-container">
                  <!--<span class="label label-primary pull-right">13</span>-->
                </span>
              </a>
            </li>
            <li><a href="compose.php">Compose</a></li>
            <!--<li class="active"><a href="read-mail.html">Read</a></li>-->
          </ul>
        </li>
      </ul>
 	<?php
      			
      		}elseif($row['user_role'] == "Viewer"){
    ?>
          <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>ARV Practice</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="practice_start.php"><i class="fa fa-circle-o"></i> Solo Practice</a></li>
            <li><a href="practiced_report.php"><i class="fa fa-circle-o"></i>All Practice</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-image"></i> <span>Manage Taskes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="viewer_task_page.php"><i class="fa fa-circle-o"></i>New Image Viewing Tasks</a></li>
            <li><a href="viewer_task_feedback_page.php"><i class="fa fa-circle-o"></i>Image Viewing Tasks Feedback</a></li>
            <li><a href="viewer_task_report_page.php"><i class="fa fa-circle-o"></i>All Image Viewing Report</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="mailbox.html">
            <i class="fa fa-envelope"></i> <span>Meassages</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="mailbox.php">Inbox
                <span class="pull-right-container">
                  <!--<span class="label label-primary pull-right">13</span>-->
                </span>
              </a>
            </li>
            <li><a href="compose.php">Compose</a></li>
            <!--<li class="active"><a href="read-mail.html">Read</a></li>-->
          </ul>
        </li>
      </ul>
    <?php
      		}
		}
	?>
    </section>
    <!-- /.sidebar -->
  </aside>