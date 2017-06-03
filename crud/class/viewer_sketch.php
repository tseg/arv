<?php 
class Viewersketch{
	
	private $conn;
	private $table_name = "tbl_viewer_sketch";
	
	public $id;
	public $Image_Path;
	public $Caption;
	public $Description;
	public $Viewer_ID;
	public $Project_Id;
	public $Date_Time;
	
	public function __construct($db){
		$this->conn = $db;	
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	}
	
	public function create(){
		$query = "INSERT INTO ".$this->table_name. " 
					(Caption,Description,Image_Path,Viewer_Id,Project_Id) 
				VALUES
					(:caption, :description, :image_Path,:Viewer_Id,:Project_Id)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':caption', $this->Caption);
        $stmt->bindParam(':description', $this->Description);
        $stmt->bindParam(':image_Path', $this->Image_Path);
		$stmt->bindParam(':Viewer_Id', $this->Viewer_ID);
		$stmt->bindParam(':Project_Id', $this->Project_Id);

        if ($stmt->execute()) {
        	return $this->conn->lastInsertId();
        }else{
        	return false;
        }
	}
	
	public function readone(){
				
			$query = "SELECT * FROM ".$this->table_name." WHERE id = ? LIMIT 0,1";

        	$stmt = $this->conn->prepare($query);
			
       	 	$stmt->bindParam(1, $this->id);
        	$stmt->execute();
			
			return $stmt;
	}
	
	public function readoneskecth(){
				
			$query = "SELECT * FROM ".$this->table_name." WHERE Viewer_ID = ? AND Project_Id = ? LIMIT 0,1";

        	$stmt = $this->conn->prepare($query);
			
       	 	$stmt->bindParam(1, $this->Viewer_ID);
       	 	$stmt->bindParam(2, $this->Project_Id);
        	$stmt->execute();
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
			$this->id = $row['id'];
        	$this->Caption = $row['Caption'];
			$this->Description = $row['Description'];
			$this->Viewer_ID = $row['Viewer_ID'];
			$this->Project_Id = $row['Project_Id'];
			$this->Date_Time = $row['Date_Time'];
			$this->Image_Path = $row['Image_Path'];
			
			return $stmt;
	}
}

?>