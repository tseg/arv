<?php
include_once '../class/config.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('Need Product ID');

$database = new Config();
$db = $database->getConnection();

include_once '../class/group.php';
$group = new Group($db);

$group->id = $id;
$group->readOne();
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
	<a class="btn btn-primary" href="index.php" role="button">Back</a>
      </p><br/>
<?php
if($_POST){

	$group->name = $_POST['name'];
	$group->description = $_POST['description'];
	$group->status = $_POST['status'];
	
	if($group->update()){
?>
<script>window.location.href='index.php'</script>
<?php
	}else{
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Fail!</strong>
</div>
<?php
	}
}
?>
<form method="post">
  <div class="form-group">
    <label for="nm">Name</label>
    <input type="text" class="form-control" name="name" value='<?php echo $group->name; ?>'>
  </div>
    <div class="form-group">
    <label for="ar">Description</label>
    <textarea class="form-control" rows="3" name="description"><?php echo $group->description; ?></textarea>
  </div>
  <div class="form-group">
    <label for="gd">Status</label>
    <input type="text" class="form-control" name="status" value='<?php echo $group->status; ?>'>
  </div>
  <button type="submit" class="btn btn-success">Submit</button>
</form>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

  </body>
</html>