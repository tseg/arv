<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class imagecrud{
    private $db;
    
    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }
    
    public function create($fname,$lname,$email,$contact,$role,$username,$password)
    {
        try
        {
			$new_password = password_hash($password, PASSWORD_DEFAULT);
            $stm = $this->db->prepare("INSERT INTO tbl_users(first_name,last_name,email_id,contact_no,user_role,user_name,password) VALUES(:fname, :lname, :email, :contact,:role,:username,:password)");
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
    
    public function getID($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tbl_users WHERE id=:id");
        $stmt->execute(array(":id"=>$id));
        $editRow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $editRow;
    }
    
    public function update($fname,$lname,$email,$contact)
    {
        try
        {
            $stmt = $this->db->prepare("UPDATE tbl_users SET first_name=:fname,last_name=:lname,email_id=:email,contact_no=:contact WHERE id=:id ");
    
            $stmt->bindparam(":fname",$fname);
            $stmt->bindparam(":lname",$lname);
            $stmt->bindparam(":email",$email);
            $stmt->bindparam(":contact",$contact);
			
            $stmt->bindparam(":id",$id);
            $stmt->execute();
   
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
	
	
	 public function updatev($email,$contact,$username,$password)
    {
        try
        {
            $stmt = $this->db->prepare("UPDATE tbl_users SET email_id=:email,contact_no=:contact,user_name=:username,password=:password WHERE id=:id ");
    
            $stmt->bindparam(":fname",$fname);
            $stmt->bindparam(":lname",$lname);
            $stmt->bindparam(":email",$email);
            $stmt->bindparam(":contact",$contact);
			
            $stmt->bindparam(":id",$id);
            $stmt->execute();
   
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
	
	
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tbl_user WHERE id=:id");
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return true;
    }

/* paging */
 
 public function dataview($query)
 {
  $stmt = $this->db->prepare($query);
  $stmt->execute();
  
 
  if($stmt->rowCount()>0)
  {
   while($row=$stmt->fetch(PDO::FETCH_ASSOC))
   {
    ?>
                <tr>
                <td><?php print($row['id']); ?></td>
				<td><?php print($row['Viewer_ID']); ?></td>
                <td><?php print($row['Caption']); ?></td>
                <td><?php print($row['Date_Time']); ?></td>
                <td><?php print($row['Image_Path']); ?></td>
				<td align="center">
				
                <a href="start_judging.php?edit_id=<?php print($row['id']); ?>"><i class="glyphicon glyphicon-edit"></i></a>
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
 
 //for viewer home page_no
 public function dataviewforViwer($query)
 {
  $stmt = $this->db->prepare($query);
  $stmt->execute();
 
  if($stmt->rowCount()>0)
  {
   while($row=$stmt->fetch(PDO::FETCH_ASSOC))
   {
    ?>
                <tr>
              
				<td><?php print($row['Viewer_Id']); ?></td>
                <td><?php print($row['Project_Id']); ?></td>
				 <td><?php print($row['Upload_Deadline']); ?></td>
				<td align="center">
                <a href="add_image_viewer.php?project_id=<?php print($row['Project_Id']); ?>"><i class="glyphicon glyphicon-edit"></i></a>
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
 
 public function dataviewforViwer2($query)
 {
  $stmt = $this->db->prepare($query);
  $stmt->execute();
  
  $date=new DateTime(); //this returns the current date time
$currentDate = $date->format('m/d/Y');
 
  if($stmt->rowCount()>0)
  {
   while($row=$stmt->fetch(PDO::FETCH_ASSOC))
   {
	   $timestamp = date('Y-m-d H:i:s');
       if($row['Notification_Time']<=$timestamp )
	   {
		 ?>
                <tr>
              
				<td><?php print($row['Name']); ?></td>
                <td><?php print($row['Question']); ?></td>
				 <td><?php print($row['Upload_Deadline']); ?></td>
				<td align="center">
				<?php
				
				if($currentDate>$row['Upload_Deadline'])
				{
					echo "Upload Time Expired";
				 ?>
				 </td></tr>
				 <?php
				}
				else
				{
				?>
				
                <a href="viewer_sketch_upload.php?task_id=<?php print($row['Project_Id']); ?>">Click here to Upload your view</i></a>
                </td>
				<?php
                }
				?>
                </tr>
                <?php
	   }
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
 
 public function dataviewforJudge($query)
 {
  $stmt = $this->db->prepare($query);
  $stmt->execute();
 
  if($stmt->rowCount()>0)
  {
   while($row=$stmt->fetch(PDO::FETCH_ASSOC))
   {
	   
    ?>
                <tr>
              
				<td><?php print($row['ImgID']); ?></td>
                <td><?php print($row['ProjectID']); ?></td>
				<td><?php print($row['JudgeID']); ?></td>
				 <td><?php print($row['Judge_Deadline']); ?></td>
				<td align="center">
                <a href="start_judging.php?edit_id=<?php print($row['ImgID']); ?>">Click here to start Judging</i></a>
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
 
  public function dataviewforJudge2($query)
 {
  $stmt = $this->db->prepare($query);
  $stmt->execute();
   $date=new DateTime(); //this returns the current date time
   $currentDate = $date->format('m/d/Y');
 
 
  if($stmt->rowCount()>0)
  {
   while($row=$stmt->fetch(PDO::FETCH_ASSOC))
   {
	      $timestamp = date('Y-m-d H:i:s');
       if($row['Notification_Time']<=$timestamp )
	   {
	    
         ?>
                <tr>
              
				<td><?php print($row['Name']); ?></td>
                <td><?php print($row['Question']); ?></td>
				<td><?php print($row['Judge_Deadline']); ?></td>
				<td align="center">
				<?php
				
				if($currentDate>$row['Judge_Deadline'])
				{
					echo "Upload Time Expired";
				 ?>
				 </td></tr>
				 <?php
				}
				else
				{
				?>
				
                 <a href="judge_task_rating.php?task_id=<?php print($row['ImgID']); ?>"><u>Click here to start Judging</u></i></a>
                </td>
				<?php
                }
				?>
                </tr>
                <?php
	   }
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
 
 
 
 //check if Viewer sketch is completed for specific viewer and specific project_id
   public function checkViewerStatus($query)
 {
  $stmt = $this->db->prepare($query);
  $stmt->execute();
   
 
  if($stmt->rowCount()>0)
  {
   ?>     
               <td>Pending <a href="#" ><font color="FF00CC">Notify him again</font></a></td>
				
                
				
	  <?php		
   
  }
  else
  {
   ?>
            
           <td>completed</td>
            
            <?php
  }
  
 }
 
 
 
 //check if Judge is completed for each Judge and specific project_id
   public function checkJudgeStatus($query)
 {
  $stmt = $this->db->prepare($query);
  $stmt->execute();
   
 
  if($stmt->rowCount()>0)
  {
   ?>     
               <td>Pending <a href="#" ><font color="FF00CC">Notify him again</font></a></td>
				
                
				
	  <?php		
   
  }
  else
  {
   ?>
            
           <td>completed</td>
            
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
  
  $stmt = $this->db->prepare($query);
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
