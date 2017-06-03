<?php

class User
{

    // database connection and table name
    private $conn;
    private $table_name = "tbl_users";

    // object properties
	public $id;
	public $firstname;
	public $lastname;
	public $contactno;
	public $email;
	public $password;
	public $username;
	public $userrole;
	public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    function create()
    {

        //$query = "INSERT INTO " . $this->table_name . " values('',?,?,?,?,?,?,?,?)";
		
		$query = "INSERT INTO " 
					. $this->table_name . 
				" SET 
					first_name = ?, last_name = ?, email_id = ?, contact_no = ?, user_role = ?, user_name = ?, password = ?, status = ?";

        $stmt = $this->conn->prepare($query);
		
        $stmt->bindParam(1, $this->firstname);
        $stmt->bindParam(2, $this->firstname);
        $stmt->bindParam(3, $this->email);
        $stmt->bindParam(4, $this->contactno);
		$stmt->bindParam(5, $this->userrole);
		$stmt->bindParam(6, $this->username);
		
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
		$stmt->bindParam(7, $this->password);
		$stmt->bindParam(8, $this->status);
		
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
	public function update()
    {
        $query = "UPDATE 
        			" . $this->table_name . "
         		SET 
        			first_name = :fname,
        			last_name = :lname,
        			email_id = :email,
        			contact_no = :contact,
        			user_role = :role,
        			user_name = :username,
        			status = :status 
        		WHERE 
        			id = :id";	

        $stmt = $this->conn->prepare($query);
    
        $stmt->bindparam(":fname", $this->firstname);
		$stmt->bindparam(":lname", $this->lastname);
        $stmt->bindparam(":email", $this->email);
        $stmt->bindparam(":contact", $this->contactno);
		$stmt->bindparam(":role", $this->userrole);
		$stmt->bindparam(":username", $this->username);
        $stmt->bindparam(":status", $this->status);
        $stmt->bindparam(":id", $this->id);
            
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
	
	public function delete(){
		
		$query = "DELETE FROM ". $this->table_name ." WHERE id=:uid";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute(array(':uid'=>$this->id));
		
		if($stmt){
			return true;	
		}else{
			return false;
		}
		
		
	}
	
    function readOne()
    {
        $query = "SELECT * FROM  ". $this->table_name ." WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->firstname = $row['first_name'];
		$this->lastname = $row['last_name'];
		$this->email = $row['email_id'];
		$this->contactno = $row['contact_no'];
		$this->userrole = $row['user_role'];
		$this->username = $row['user_name'];
		$this->status = $row['status'];
    }

    function readAll($page, $from_record_num, $records_per_page)
    {

        $query = "SELECT * FROM  " . $this->table_name . " ORDER BY user_name ASC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
	
	
	function readAllUser()
    {

        $query = "SELECT * FROM  " . $this->table_name . " ORDER BY User_Name ASC";

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
	
	function readOneByIdUserType()
    {
		/*
		*TO DO: add where criteria using user_id and user_type
		*/
        $query = "SELECT * FROM  " . $this->table_name . " WHERE id = ? and User_Type = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->user_type);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->user_name = $row['User_Name'];
        $this->user_id = $row['User_Id'];
        $this->user_type = $row['User_Type'];
        $this->status = $row['Status'];
    }

	function updatestatus()
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

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

	/*
    function delete()
    {
		
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($result = $stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }*/
}

?>
