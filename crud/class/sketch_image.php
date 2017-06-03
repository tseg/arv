<?php

class SketchJudgeImage{
	
	private $conn;
	private $table_name = "tbl_image_judge_sketch"; 
	
	public $judgeid;
	public $sketchid;
	public $imageup;
	public $imagedown;
	public $datetime;
	public $projectid;
	public $imageid;
	public $veiwerid;
	public $rateup;
	public $confup;
	public $ratedown;
	public $confdown;
	public $id;
	
	public function __construct($db){
		$this->conn = $db;
	}
	
	public function create(){
        //$query = "INSERT INTO " . $this->table_name . " values('',?,?,?,?,?,'','',?,?)";
        
		$query = "INSERT INTO " 
					. $this->table_name . 
				" SET 
					Judge_Id = ?, Image_Id = ?, Image_Id_Up = ?, Image_Id_Down = ?, Date_Time = ?, Project_Id = ?, VeiwerId = ?";
		
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->judgeid);
		$stmt->bindParam(2, $this->imageid);
        $stmt->bindParam(3, $this->imageup);
        $stmt->bindParam(4, $this->imagedown);
        $stmt->bindParam(5, $this->datetime);
        $stmt->bindParam(6, $this->projectid);
        $stmt->bindParam(7, $this->veiwerid);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }  
	}
	
	function save_image_rating()
	{

        $query = "UPDATE ".$this->table_name.
				" SET
					Rate_Up = :rateup,
					Confidence_Value_Up = :confup,
					Rate_Down = :ratedown,
					Confidence_Value_Down = :confdown
				WHERE
					Id = :id";

        $stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(':rateup', $this->rateup);
		$stmt->bindParam(':confup', $this->confup);
		$stmt->bindParam(':ratedown', $this->ratedown);
		$stmt->bindParam(':confdown', $this->confdown);
		$stmt->bindParam(':id', $this->id);
		
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	function save_viewer_sketch()
	{

        $query = "UPDATE ".$this->table_name.
				" SET
					Sketch_Id = :sketch
				WHERE
					Project_Id = :pid AND VeiwerId = :vid";

        $stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(':sketch', $this->sketchid);
		$stmt->bindParam(':pid', $this->projectid);
		$stmt->bindParam(':vid', $this->veiwerid);
		
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	public function readimages(){
		
        $query = "SELECT * FROM  " . $this->table_name . " WHERE Project_Id = ? AND VeiwerId = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->projectid);
		$stmt->bindParam(2, $this->veiwerid);
        $stmt->execute();
		
		return $stmt;
	}
	
	function check_image(){
		$query = "SELECT * FROM " .$this->table_name. " WHERE Judge_Id = ? AND Sketch_Id = ? AND Project_Id = ? LIMIT 0,1";
		
 		$stmt = $this->conn->prepare($query);
		
       	$stmt->bindParam(1, $this->judgeid);
		$stmt->bindParam(2, $this->sketchid);
		$stmt->bindParam(3, $this->projectid);
        $stmt->execute();				
			
		return $stmt;
	}
	
	public function readviewer(){

        $query = "SELECT DISTINCT(VeiwerId) FROM  " . $this->table_name . " WHERE Project_Id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->projectid);
        $stmt->execute();
		
		return $stmt;
	}
	
		
	public function read_project_rating(){
		
        $query = "SELECT * FROM  " . $this->table_name . " WHERE Project_Id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->projectid);
        $stmt->execute();
		
		return $stmt;
	}
	
	public function read_project_by_viewer(){
		
        $query = "SELECT * FROM  " . $this->table_name . " WHERE VeiwerId = ? GROUP BY VeiwerId, Project_Id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->veiwerid);
        $stmt->execute();
		
		return $stmt;
	}
	
	public function read_by_viwer_project(){
		
        $query = "SELECT * FROM  " . $this->table_name . " WHERE Project_Id = ? AND VeiwerId = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->projectid);
		$stmt->bindParam(2, $this->veiwerid);
        $stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->judgeid = $row['Judge_Id'];
        $this->sketchid = $row['Sketch_Id'];
		$this->veiwerid = $row['VeiwerId'];
		
	}
	
	public function read_by_judge_project(){
		
        $query = "SELECT * FROM  " . $this->table_name . " WHERE Project_Id = ? AND VeiwerId = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->projectid);
		$stmt->bindParam(2, $this->veiwerid);
        $stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->judgeid = $row['Judge_Id'];
        $this->sketchid = $row['Sketch_Id'];
		$this->veiwerid = $row['VeiwerId'];
		
	}
	
	public function read_project_viewer_join(){
		$query = "SELECT * FROM tbl_image_judge_sketch as ijs INNER JOIN tbl_project as p 
					ON (ijs.Project_Id = p.Id)
					WHERE VeiwerId = ?  GROUP BY ijs.Project_Id";
		
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->veiwerid);
        $stmt->execute();
		
		return $stmt;
	}
	
	public function find_viewer_sketch(){
		$query = "SELECT * FROM `tbl_image_judge_sketch` WHERE `Image_Id`= ? AND `VeiwerId`= ? AND `Project_Id`= ? LIMIT 0,1";
		
		$stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->imageid);
        $stmt->bindParam(2, $this->veiwerid);
		$stmt->bindParam(3, $this->projectid);
        $stmt->execute();
		
		return $stmt;
	}
	
	function delete_judge_viewer_iamge()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE Project_Id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->projectid);

        if ($result = $stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
}
?>