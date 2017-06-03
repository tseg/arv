<?php

class Project
{

    // database connection and table name
    private $conn;
    private $table_name = "tbl_project";

    // object properties
    public $id;
    public $Name;
    public $Question;
    public $Date_Time;
    public $Online_Trade_Direction;
    public $Revenue;
    public $Judge_Direction;
	public $Place_Date_Time;
    public $Exit_Date_Time;
    public $Investment;
    public $Judge_Deadline;
    public $Upload_Deadline;
    public $Notification_Time;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function create()
    {
        //$query = "INSERT INTO " . $this->table_name . " values('NULL',?,?,?,'NULL','NULL','NULL',?,?,?,?,?,?,?)";
		
		$query = "INSERT INTO " 
					. $this->table_name . 
				" SET Name = ?, Question = ?, Date_Time = ?, Investment = ?, Upload_Deadline = ?, Notification_Time = ?, Exit_Date_Time = ?, Place_Date_Time = ?, Judge_Deadline = ?, Status = ?";
		
		
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->Name);
        $stmt->bindParam(2, $this->Question);
        $stmt->bindParam(3, $this->Date_Time);
		$stmt->bindParam(4, $this->Investment);
		$stmt->bindParam(5, $this->Upload_Deadline);
		$stmt->bindParam(6, $this->Notification_Time);
		$stmt->bindParam(7, $this->Exit_Date_Time);
		$stmt->bindParam(8, $this->Place_Date_Time);
		$stmt->bindParam(9, $this->Judge_Deadline);
        $stmt->bindParam(10, $this->status);
		
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return 0;
        }

    }
    
    function getallprojects($db) {
        $stmt = $db->query("SELECT * FROM tbl_project ORDER BY FName ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
	
	
    function readAll($page, $from_record_num, $records_per_page)
    {

        $query = "SELECT * FROM  " . $this->table_name . " ORDER BY FName ASC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    
    public function countAll()
    {

        $query = "SELECT id FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }

    
    function readOne()
    {

        $query = "SELECT * FROM  " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->Name = $row['Name'];
        $this->Question = $row['Question'];
		$this->Investment = $row['Investment'];
		$this->Upload_Deadline = $row['Upload_Deadline'];
		$this->Notification_Time = $row['Notification_Time'];
		$this->Exit_Date_Time = $row['Exit_Date_Time'];
		$this->Place_Date_Time = $row['Place_Date_Time'];
		$this->Judge_Deadline = $row['Judge_Deadline'];
        $this->status = $row['Status'];
		$this->Judge_Direction = $row['Judge_Direction'];
		$this->Online_Trade_Direction = $row['Online_Trade_Direction'];
		
    }
	
	function readProjectDirection($viwerid){
		$query = "SELECT * FROM tbl_project as P JOIN tbl_image_judge_sketch as IJS 
						ON 
							P.Id = IJS.Project_Id 
						WHERE 
							Online_Trade_Direction != 'NULL' AND Judge_Direction != 'NULL' AND IJS.VeiwerId = ? 
						GROUP BY 
							IJS.Project_Id, IJS.VeiwerId 
						ORDER BY 
							`P`.`Date_Time` DESC";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $viwerid);
  		$stmt->execute();

  		return $stmt;
	}
	
    function update()
    {
        $query = "UPDATE
					" . $this->table_name . "
				SET
					Name = :name,
					Question = :question,
					Investment = :invest,
					Upload_Deadline = :upload,
					Notification_Time = :notification,
					Exit_Date_Time = :tradeexit,
					Place_Date_Time = :tradeplace,
					Judge_Deadline = :judge,
					Status = :status
				WHERE
					Id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->Name);
		$stmt->bindParam(':question', $this->Question);
        $stmt->bindParam(':invest', $this->Investment);
        $stmt->bindParam(':upload', $this->Upload_Deadline);
		$stmt->bindParam(':notification', $this->Notification_Time);
		$stmt->bindParam(':tradeexit', $this->Exit_Date_Time);
        $stmt->bindParam(':tradeplace', $this->Place_Date_Time);
        $stmt->bindParam(':judge', $this->Judge_Deadline);
		$stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
	function update_judge_direction()
    {
        $query = "UPDATE
					" . $this->table_name . "
				SET
					Judge_Direction = :direction,
					Status = :status
				WHERE
					Id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':direction', $this->Judge_Direction);
		$stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
	
	function update_status()
    {
        $query = "UPDATE
					" . $this->table_name . "
				SET
					Status = :status
				WHERE
					Id = :id";

        $stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // delete the product
    function delete()
    {

        $query = "DELETE FROM " . $this->table_name . " WHERE Id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($result = $stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
	
	public function dataviewforViwer2($viwerid)
 	{
 		
		$query = "SELECT * FROM 
					tbl_viewer_group as vg INNER JOIN tbl_project as p 
				ON vg.Project_Id = p.ID 
					WHERE Viewer_Id = ? ORDER BY Upload_Deadline ASC";
  			
  		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $viwerid);
  		$stmt->execute();
  
  		return $stmt;
  
 	}
	
	public function check_image_uploade($viwerid, $projectid )
 	{
 		
		$query = "SELECT * FROM tbl_viewer_group as vg right join tbl_viewer_sketch as vs on (vg.Project_Id = vs.Project_Id AND vs.Viewer_Id = vg.Viewer_Id) where vg.Viewer_Id = ? AND vg.Project_Id = ?";
  			
  		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $viwerid);
		$stmt->bindParam(2, $projectid);
  		$stmt->execute();
  		$num = $stmt->rowCount();
  		return $num;
 	}
	
	
	public function dataviewforjudge($judgeid)
 	{
 		
		$query = "SELECT * FROM 
					tbl_judge_group as jg INNER JOIN tbl_project as p 
				ON jg.Project_Id = p.ID 
					WHERE jg.Judge_Id = ? ORDER BY `p`.`Judge_Deadline`  ASC";
  			
  		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $judgeid);
  		$stmt->execute();
  
  		return $stmt;
  
 	}


}
