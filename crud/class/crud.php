<?php

class Crud{
        public function __construct($db)
    {
        $this->conn = $db;
    }
    
    function createViewerGroup($viewerid,$projectid,$date)
    {

        //write query
        $query = "INSERT INTO  tbl_viewer_group values('',?,?,?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $viewerid);
        $stmt->bindParam(2, $projectid);
        $stmt->bindParam(3, $date);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    }
    
    function createJudgeGroup($judgeid,$projectid,$date){
        $query = "INSERT INTO tbl_judge_group values ('',?,?,?)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $judgeid);
        $stmt->bindParam(2, $projectid);
        $stmt->bindParam(3, $date);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }   
    }
    
    function createImageSketch($judgeid,$imageup,$imagedown,$datetime,$projectid,$veiwerid,$sketchid){
        $query = "INSERT INTO tbl_image_judge_sketch values ('',?,?,?,?,?,'','',?,?)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $judgeid);
        $stmt->bindParam(2, $sketchid);
        $stmt->bindParam(3, $imageup);
        $stmt->bindParam(4, $imagedown);
        $stmt->bindParam(5, $datetime);
        $stmt->bindParam(6, $projectid);
        $stmt->bindParam(7, $veiwerid);
        
        /*
        $stmt->bindParam(1, $judgeid);
        $stmt->bindParam(2, $imageup);
        $stmt->bindParam(3, $imagedown);
        $stmt->bindParam(4, $datetime);
        $stmt->bindParam(5, $projectid);
        $stmt->bindParam(6, $veiwerid);
        */
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }  
    }
    
    function createProjectSetting($judge,$upload,$notif,$exit,$start,$invest,$date,$project){
       
        try {
            $stmt = $this->conn->prepare("INSERT INTO tbl_setting(`Judge_Deadline`, `Upload_Deadline`, `Notification_Time`, `Exit_Date_Time`, `Place_Date_Time`, `Investment`, `Date_Time`, `Project_Id`)"
                    . " VALUES(:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8)");
            $stmt->execute(array(':field1' => $judge, ':field2' => $upload, ':field3' => $notif, ':field4' => $exit, ':field5' => $start, ':field6' =>$invest, ':field7' => $date, 'field8' => $project));
            $affected_rows = $stmt->rowCount();
            return $affected_rows;
            //$query = "INSERT INTO tbl_setting VALUES ('',?,?,?,?,?,?,?,?)";
            //$this->conn->exec("INSERT INTO `tbl_setting`(`Judge_Deadline`, `Upload_Deadline`, `Notification_Time`, `Exit_Date_Time`, `Place_Date_Time`, `Investment`, `Date_Time`, `Project_Id`) VALUES (tes,t,t,t,t,t,1,2)");
                    /*($judge, $upload, $notif, $exit, $start, $invest, $date, $project)");*/
            //$insertId = $this->conn->lastInsertId();    
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
       
       
        /*
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1,$judge);
        $stmt->bindParam(2,$upload);
        $stmt->bindParam(3,$notif);
        $stmt->bindParam(4,$exit);
        $stmt->bindParam(5,$start);
        $stmt->bindParam(6,$invest);
        $stmt->bindParam(7,$date);
        $stmt->bindParam(8,$project);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        } */
    }

	public function create($fname,$lname,$email,$contact,$role,$username,$password)
    {
        try
        {
			$new_password = password_hash($password, PASSWORD_DEFAULT);
            $stm = $this->conn->prepare("INSERT INTO tbl_users(first_name,last_name,email_id,contact_no,user_role,user_name,password) VALUES(:fname, :lname, :email, :contact,:role,:username,:password)");
            $stm->bindparam(":fname",$fname);
            $stm->bindparam(":lname",$lname);
            $stm->bindparam(":email",$email);
            $stm->bindparam(":contact",$contact);
			$stm->bindparam(":role",$role);
			$stm->bindparam(":username",$username);
			$stm->bindparam(":password",$new_password);
            $stm->execute();
            return true;
            
        }
        catch(ErrorException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }
	public function updatev($email,$contact,$username,$pass_word,$Viewer_ID)
    {
        try
        {
			$pass_word = password_hash($pass_word, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE tbl_users SET email_id=:email,contact_no=:contact,user_name=:username,password=:pass_word WHERE id=:Viewer_ID ");
			
			$stmt->bindparam(":email",$email);
			$stmt->bindparam(":contact",$contact);
			
            $stmt->bindparam(":username",$username);
            $stmt->bindparam(":pass_word",$pass_word);
           $stmt->bindparam(":Viewer_ID",$Viewer_ID);
            
			
            //$stmt->bindparam(":id",$id);
            $stmt->execute();
   
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
	
	
	public function updateProject($jd,$vd,$nd,$projectid)
    {
        try
        {
			
            $stmt = $this->conn->prepare("UPDATE tbl_setting SET Judge_Deadline=:jd,Upload_Deadline=:vd,Notification_Time=:nd WHERE Project_Id=:projectid ");
			
			$stmt->bindparam(":jd",$jd);
			$stmt->bindparam(":vd",$vd);
			
            $stmt->bindparam(":nd",$nd);
           
          $stmt->bindparam(":projectid",$projectid);
            
			
            //$stmt->bindparam(":id",$id);
            $stmt->execute();
   
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
	
	
	
	
    public function getID($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_users WHERE id=:id");
        $stmt->execute(array(":id"=>$id));
        $editRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $editRow;
    }
    
    public function update($email,$contact,$usename,$password)
    {
        try
        {
            $stmt = $this->db->prepare("UPDATE tbl_users SET email_id=:email,contact_no=:contact,user_name=:username,password=:password WHERE id=:id ");
    
           
            $stmt->bindparam(":email",$email);
            $stmt->bindparam(":contact",$contact);
			$stmt->bindparam(":username",$usename);
            $stmt->bindparam(":password",$password);
            $stmt->bindparam(":id",$id);
            $stmt->execute();
   
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
	
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM tbl_user WHERE id=:id");
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return true;
    }

/* paging */
 
 public function dataview($query)
 {
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
 
  if($stmt->rowCount()>0)
  {
   while($row=$stmt->fetch(PDO::FETCH_ASSOC))
   {
    ?>
                <tr>
                <td><?php print($row['id']); ?></td>
                <td><?php print($row['first_name']); ?></td>
                <td><?php print($row['last_name']); ?></td>
                <td><?php print($row['email_id']); ?></td>
                <td><?php print($row['contact_no']); ?></td>
				<td><?php print($row['user_role']); ?></td>
                <td align="center">
                <a href="user_edit.php?vid=<?php echo $row['id']; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                </td>
                <td align="center">
                	<a class="delete_product" data-id="<?php echo $row['id']; ?>" href="javascript:void(0)"><i class="glyphicon glyphicon-remove-circle"></i></a>
                </td>
                </tr>
                <?php
   }
  }
  else
  {
   ?>
            <tr>
            <td>Nothing here...</td>
            </tr>
            <?php
  }
  
 }
    public function paging($query,$records_per_page)
    {
        $starting_position=0;
        if(isset($_GET["page_no"]))
        {
            $starting_position=($_GET["page_no"]-1)*$records_per_page;
        }
        $query2=$query." limit $starting_position,$records_per_page";
        return $query2;
    }
 
 public function paginglink($query,$records_per_page)
 {
  
  $self = $_SERVER['PHP_SELF'];
  
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  
  $total_no_of_records = $stmt->rowCount();
  
  if($total_no_of_records > 0)
  {
   ?><ul class="pagination"><?php
   $total_no_of_pages=ceil($total_no_of_records/$records_per_page);
   $current_page=1;
   if(isset($_GET["page_no"]))
   {
    $current_page=$_GET["page_no"];
   }
   if($current_page!=1)
   {
    $previous =$current_page-1;
    echo "<li><a href='".$self."?page_no=1'>First</a></li>";
    echo "<li><a href='".$self."?page_no=".$previous."'>Previous</a></li>";
   }
   for($i=1;$i<=$total_no_of_pages;$i++)
   {
    if($i==$current_page)
    {
     echo "<li><a href='".$self."?page_no=".$i."' style='color:red;'>".$i."</a></li>";
    }
    else
    {
     echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
    }
   }
   if($current_page!=$total_no_of_pages)
   {
    $next=$current_page+1;
    echo "<li><a href='".$self."?page_no=".$next."'>Next</a></li>";
    echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Last</a></li>";
   }
   ?></ul><?php
  }
 }


}
?>
