<?php 

class Image{
	
	private $conn;
	private $table_name = "tbl_image";
	
	public $id;
	public $caption;
	public $description;
	public $filename;
	public $caption2;
	public $description2;
	public $filename2;
	
	public function __construct($db){
		$this->conn = $db;	
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	public function create(){
		
		$query = "INSERT INTO " .$this->table_name. " SET Image_Path = ?, Caption = ?, Description = ? ";
		
		$stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(1, $this->filename);
		$stmt->bindParam(2, $this->caption);
		$stmt->bindParam(3, $this->description);
		
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	public function create2(){
		
		//$query = "INSERT INTO " .$this->table_name. " SET Image_Path = ?, Caption = ?, Description = ? ";
		$query = "INSERT INTO " .$this->table_name. " SET Image_Path = ?, Caption = ? , Description = ?, Image_Path2 = ?, Caption2 = ?, Description2 = ? ";
		
		$stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(1, $this->filename);
		$stmt->bindParam(2, $this->caption);
		$stmt->bindParam(3, $this->description);
		$stmt->bindParam(4, $this->filename2);
		$stmt->bindParam(5, $this->caption2);
		$stmt->bindParam(6, $this->description2);
		
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	public function update(){
		
		$query = "UPDATE " .$this->table_name." SET Image_Path = :path, Caption = :caption, Description = :des WHERE Id = :id";
		
		$stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(":path", $this->filename);
		$stmt->bindParam(":caption", $this->caption);
		$stmt->bindParam(":des", $this->description);
		$stmt->bindParam(":id", $this->id);
		
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	
	public function update2(){
		
		$query = "UPDATE " .$this->table_name." SET Image_Path = :path, Caption = :caption, Description = :des, Image_Path2 = :path2, Caption2 = :caption2, Description2 = :des2 WHERE Id = :id";
		
		$stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(":path", $this->filename);
		$stmt->bindParam(":caption", $this->caption);
		$stmt->bindParam(":des", $this->description);
		$stmt->bindParam(":path2", $this->filename2);
		$stmt->bindParam(":caption2", $this->caption2);
		$stmt->bindParam(":des2", $this->description2);
		$stmt->bindParam(":id", $this->id);
		
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	
	
	public function readall(){
		
		$query = "SELECT FROM " .$this->table_name. " ";
		
		$stmt = $this->conn->prepare($query);
		
		$stmt->execute();
		
		return $stmt;
	}
	
	public function readone(){		
		
		$query = "SELECT * FROM " .$this->table_name. " WHERE Id = ?";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		
		$stmt->execute();
		
		return $stmt;	
	}
	
	public function readallimages(){
		$sth = $this->conn->prepare("SELECT Id, Image_Path, Image_Path2 FROM tbl_image");
		$sth->execute();
		
		return $sth;	
	}
	
	public function readimages_tbl_project(){
		$stmp = $this->conn->prepare("SELECT Image_Id, Image_Id_Up, Image_Id_Down FROM tbl_image_judge_sketch");
		$stmp->execute();
		
		return $stmp;	
	}
	
	public function readdownimages(){
		$stmp = $this->conn->prepare("SELECT Image_Id_Up, Image_Path FROM tbl_image_judge_sketch join tbl_image on (Image_Id_Up = tbl_image.Id)");
		$stmp->execute();
		
		return $stmp;	
	}
	
	public function readupimages(){												
		$stmd = $this->conn->prepare("SELECT Image_Id_Down, Image_Path FROM tbl_image_judge_sketch join tbl_image on (Image_Id_Down = tbl_image.Id)");
		$stmd->execute();
		
		return $stmd;
	}
	
	public function delete(){
		$res=mysql_query("SELECT file FROM tbl_uploads WHERE id=".$_GET['remove_id']);
		$row=mysql_fetch_array($res);
		mysql_query("DELETE FROM tbl_uploads WHERE id=".$_GET['remove_id']);
		unlink("uploads/".$row['file']);
	}
}

?>