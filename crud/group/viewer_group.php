<?php
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$records_per_page = 5;

$from_record_num = ($records_per_page * $page) - $records_per_page;

include_once '../class/config.php';
include_once '../class/group.php';

$database = new Config();
$db = $database->getConnection();

$group = new Group($db);

$stmt = $group->readAll($page, $from_record_num, $records_per_page);
$num = $stmt->rowCount();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tutorial-06</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">

      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  </head>
  <body>
  <p><br/></p>
    <div class="container">
      <p>
	<a class="btn btn-primary" href="modaltest2.php" role="button">Add Data</a>
      </p>
<?php
if($num>0){
?>
	<table class="table table-bordered table-hover table-striped">
	<caption>Personal Data Table</caption>
	<thead>
	 <tr>
          <th>#</th>
          <th>Name</th>
          <th>Description</th>
          <th>status</th>
          <th>Created Date</th>
          <th>Action</th>
        </tr>
	</thead>
	<tbody>
<?php
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
$gstatus = $row['Status'];
extract($row);
?>
<tr>
	<?php echo "<td>{$Id}</td>" ?>
	<?php echo "<td>{$Name}</td>" ?>
	<?php echo "<td>{$Description}</td>" ?>
	
	<?php if($gstatus == 1){
		echo "<td> Active </td>";
		}
		else{
		echo "<td> Inactive </td>";
		}
		?>
	<?php //if({$row['Status'] == 1) { echo "<td> Active </td>"}else{ echo "<td> Inactive </td>" } ?>
	<?php echo "<td>{$Created_Date}</td>" ?>
	<?php echo "<td width='150px'>
	    <a class='btn btn-warning btn-sm' href='update.php?id={$Id}' role='button'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>
	    <a class='btn btn-danger btn-sm' href='delete.php?id={$Id}' role='button'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a>" ?>
		<?php
		if($gstatus == 1){
			echo "<a class='btn btn-default btn-sm' href='updatestatus.php?id={$Id}' role='button'><span class='glyphicon glyphicon-minus-sign' aria-hidden='true'></span></a></td>";
		}
		else{
			echo "<a class='btn btn-info btn-sm' href='updatestatus.php?id={$Id}' role='button'><span class='glyphicon glyphicon-plus-sign' aria-hidden='true'></span></a></td>";
		}
           ?>
                                
</tr>
<?php
}
?>
	</tbody>
      </table>
<?php
$page_dom = "index.php";
include_once '../class/pagination.inc.php';
}
else{
?>
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Warning!</strong> Data is still empty
</div>
<?php
}
?>
    </div>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>