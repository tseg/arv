
<?php
//include_once 'header_judge.php';
include_once 'dbconfig.php';

  
//$Sketch_ID = $_SESSION['edit_id'];




error_reporting(~E_NOTICE); // avoid notice

	//Just to randomly assign value to the image

$a=array(7,8,6,1,5);
$random_keys=array_rand($a,3);
$rx=$a[$random_keys[0]];
$ry=$a[$random_keys[1]];





if (isset($_POST['btnsave'])) {
//header("Refresh: 5; URL:index.php");
  // $rating_up = $_POST['1']; // user name
   // $rating_down = $_POST['2']; // user email
	
   	$date_time=$_POST['Image']; 
	$Judge_ID=$_POST['Image']; 
	$Image_ID=$_POST['Image']; 
	$ppid=$_SESSION['prID'];
	
	//Just to randomly assign value to the image
	if($rx>$ry)
	{
	$rating_up = $_POST['1'];	
	$rating_down = $_POST['2'];
	$confidence_up=$_POST['3'];
	$confidence_down=$_POST['4'];
	$Image_id_up=$_POST['5'];
	$Image_id_down=$_POST['6'];
	
	
	}
	else
	{
	$rating_up = $_POST['2'];	
    $rating_down = $_POST['1'];
	$confidence_up=$_POST['4'];
	$confidence_down=$_POST['3'];
	$Image_id_up=$_POST['6'];
	$Image_id_down=$_POST['5'];
	}
	
	//$viewer_id= $_SESSION['user_session'];
	
	
	
	$viewer_id= $_SESSION['user_session'];
	$Sketch_ID = $_GET['task_id'];
	//$imageid = $_GET['edit_id'];

    $imgFile = $_FILES['Image']['name'];
    $tmp_dir = $_FILES['Image']['tmp_name'];
    $imgSize = $_FILES['Image']['size'];


     if (empty($rating_up )) {
		// break;
        $errMSG = "Please caption for your image.";
    } else if (empty($description)) {
        $errMSG = "Please Enter Description.";
    } else if (empty($imgFile)) {
        $errMSG = "Please Select Image File.";
    }
		$prj=$_SESSION['prID'];

        $stmt = $DB_con->prepare('INSERT INTO tbl_image_judge_sketch(Judge_Id,Sketch_Id,Image_Id_Up,Image_Id_Down,Rate_Up,Rate_Down,Confidence_Value_Up,Confidence_Value_Down,Project_Id) VALUES(:viewer_id, :Sketch_ID,:Image_id_up,:Image_id_down,:rating_up,:rating_down,:confidence_up,:confidence_down,:prj)');
        $stmt->bindParam(':viewer_id', $viewer_id);
        $stmt->bindParam(':Sketch_ID', $Sketch_ID);
		
		$stmt->bindParam(':Image_id_up', $Image_id_up);
		$stmt->bindParam(':Image_id_down', $Image_id_down);
		
		$stmt->bindParam(':rating_up', $rating_up);
        $stmt->bindParam(':rating_down', $rating_down);
		$stmt->bindParam(':confidence_up', $confidence_up);
		$stmt->bindParam(':confidence_down', $confidence_down);
		$stmt->bindParam(':prj', $prj);
		//$stmt->bindParam(':Project_Id', $ppid);
        /*$stmt->bindParam(':Rate_Up', $rating_up);
		$stmt->bindParam(':Rate_Down', $rating_down);*/
	
        if ($stmt->execute()) {
		   
			
			//unset($_POST);
			
		// header('Location:'.$_SERVER['PHP_SELF']);
			//echo "<script type='text/javascript'>alert('submitted successfully!')</script>";

		   $successMSG = "new record succesfully inserted ...";
        } else {
            $errMSG = "error while inserting....";
			           // header("refresh:5;index.php"); // redirects image view page after 5 seconds.

        }
		
		?>
		<script type="text/javascript">
		window.location = "judge_task_page.php";
		</script>      
        <?php
	
   
}


?>



<div class="row">

<?php
$stmt = $DB_con->prepare('SELECT * FROM tbl_image order by rand() limit 2');
$stmt->execute();
$i=0;
$j=2;
$m=4;

if ($stmt->rowCount() > 0) {
	

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          
	  $i++;
	  $j++;
	  $m++;
        extract($row);
        ?>
	
            <div class="col-xs-6">
                <p class="page-header"><?php echo $row['Caption'] . "&nbsp;/" . $row['Description']; ?></p>
                <img src="upload/<?php echo $row['Image_Path'];?>" class="img-rounded" width="200px" height="200px" />
				<div class="col-lg-4">	
				
				<form role="form" method="post">			
									<tr>
                                        <td>Rate</td>
                                        <td><input type='number' name="<?php echo htmlspecialchars($i); ?>" id="rate" required="required"min="1.0" max="5.0"class='form-control'></td>
										<td>Confidence Value</td>
                                        <td><input type='number' name="<?php echo htmlspecialchars($j); ?>" required="required"id="confidence" min="1" max="10" class='form-control'></td>
										<!-- To hold the current Id of image being viewed-->                                        
                                        <td><input type='hidden' name="<?php echo htmlspecialchars($m);?>" value="<?php echo $row['Id']; ?>" class='form-control'></td>

                                        
                                    </tr>	
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
<!--<a class="btn btn-info" href="edit_image.php?edit_id=<?php echo $row['Id']; ?>" title="click for edit" onclick="return confirm('sure to edit ?')">Save</a> -->
<div style="text-align:center">
  <fieldset>
	<button type="submit" name="btnsave" class="btn btn-default">
                                            <span class="glyphicon glyphicon-save"></span> &nbsp; save
                                        </button>
									 </fieldset>
	</div>
                            </form>	
										
<?php
//include_once 'footer.php';

?>