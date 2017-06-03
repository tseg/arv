<?php

class Practice
{

    // database connection and table name
    private $conn;
    private $table_name = 'tbl_practice';

    // object properties
    public $id;
    public $Image_Path;
    public $Caption;
	public $Description;
    public $Viewer_ID;
    public $Practice_Name;
	public $Feedack_Image;
    public $Status;
	public $Date_Time;
	
	public $pereference;

    public function __construct($db)
    {
        $this->conn = $db;
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    public function create()
    {
				$query = "INSERT INTO " 
					. $this->table_name . 
				" SET Viewer_ID = ?, Date_Time = ?, Status = ?";
		

        			$stmt = $this->conn->prepare($query);

        			$stmt->bindParam(1, $this->Viewer_ID);
        			$stmt->bindParam(2, $this->Date_Time);
        			$stmt->bindParam(3, $this->status);
					
					if ($stmt->execute()) {
            			return $this->conn->lastInsertId();
        			} else {
            			return false;
        			}
	}
	public function create_practice_with_Name(){	

				$query = "INSERT INTO " 
					. $this->table_name . 
				" SET Viewer_ID = ?, Practice_Name = ?, Date_Time = ?, Status = ?";
		
        			$stmt = $this->conn->prepare($query);

        			$stmt->bindParam(1, $this->Viewer_ID);
					$stmt->bindParam(2, $this->Practice_Name);
        			$stmt->bindParam(3, $this->Date_Time);
        			$stmt->bindParam(4, $this->status);
					
					if ($stmt->execute()) {
            			return $this->conn->lastInsertId();

        			} else {
            			return false;
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
    
	function readAllPractice($vid)
    {

        $query = "SELECT * FROM  " . $this->table_name . " WHERE Viewer_ID = ? ORDER BY Date_Time ASC";

        $stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $vid);
		
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
        $this->Image_Path = $row['Image_Path'];
		$this->Caption = $row['Caption'];
		$this->Description = $row['Description'];
		$this->Viewer_ID = $row['Viewer_ID'];
		$this->Practice_Name = $row['Practice_Name'];
		$this->Feedack_Image = $row['Feedack_Image'];
		$this->Status = $row['Status'];
        $this->Date_Time = $row['Date_Time'];
		
    }

	
    function image_viewing()
    {

		
		//UPDATE `tbl_practice` SET id,Image_Path,Caption,Description,Viewer_ID,Practice_Name,Feedack_Image,Date_Time,Status
        $query = "UPDATE
					" . $this->table_name . "
				SET
					Image_Path = :path,
					Caption = :cap,
					Description = :des,
					Status = :status
				WHERE
					id = :id AND Viewer_ID = :vid";

        $stmt = $this->conn->prepare($query);
		
        $stmt->bindParam(':path', $this->Image_Path);
		$stmt->bindParam(':cap', $this->Caption);
        $stmt->bindParam(':des', $this->Description);
		$stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
		$stmt->bindParam(':vid', $this->Viewer_ID);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
	function image_feedback()
    {

        $query = "UPDATE
					" . $this->table_name . "
				SET
					Feedack_Image = :path
				WHERE
					id = :id AND Viewer_ID = :vid";

        $stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(':path', $this->Feedack_Image);
        $stmt->bindParam(':id', $this->id);
		$stmt->bindParam(':vid', $this->Viewer_ID);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
	
	function practice_status()
    {

		
		//UPDATE `tbl_practice` SET id,Image_Path,Caption,Description,Viewer_ID,Practice_Name,Feedack_Image,Date_Time,Status
        $query = "UPDATE
					" . $this->table_name . "
				SET
					Status = :status
				WHERE
					id = :id AND Viewer_ID = :vid";

        $stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
		$stmt->bindParam(':vid', $this->Viewer_ID);

        // execute the query
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
}
