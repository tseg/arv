<?php
include_once '../class/config.php';

$database = new Config();
$db = $database->getConnection();

include_once '../class/group.php';
$group = new Group($db);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tutorial-06</title>

    <!-- Bootstrap -->
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
    if ($_POST) {

        $group->name = $_POST['name'];
        $group->description = $_POST['description'];
        $group->status = $_POST['status'];
        
		// set your default timezone
		date_default_timezone_set('Asia/Manila');
		$group->created = date('Y-m-d H:i:s');

        if ($group->create()) {
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <strong>Success!</strong>  <a href="index.php">View Data</a>.
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <strong>Fail!</strong>
            </div>
            <?php
        }
    }
    ?>
    <form method="post">
        <div class="form-group">
            <label for="nm">Name</label>
            <input type="text" class="form-control" id="nm" name="name">
        </div>
        <div class="form-group">
            <label for="ar">Description</label>
            <textarea class="form-control" rows="3" id="ar" name="description"></textarea>
        </div>
		<div class="form-group">
            <label for="tl">Status</label>
            <input type="text" class="form-control" id="tl" name="status">
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>