<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create product
    function create()
    {
        //write query
        $query = "INSERT INTO " . $this->table_name . " values('',?,?,?,'','','',?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->Name);
        $stmt->bindParam(2, $this->Question);
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

        function readAllJudge()
    {

        $query = "SELECT * FROM  " . $this->table_name . " ORDER BY FName ASC ";

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

    // used when filling up the update product form
    function readOne()
    {

        $query = "SELECT * FROM  " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->fname = $row['FName'];
        $this->lname = $row['LName'];
        $this->email = $row['Email'];
        $this->phone = $row['Phone'];
    }

    function readOneByNameEmail()
    {

        $query = "SELECT * FROM  " . $this->table_name . " WHERE Email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['Id'];
		$this->fname = $row['FName'];
        $this->lname = $row['LName'];
        $this->email = $row['Email'];
        $this->phone = $row['Phone'];
    }
	
    function update()
    {


        $query = "UPDATE
					" . $this->table_name . "
				SET
					FName = :fname,
					LName = :lname,
					Email = :email,
					Phone = :phone
				WHERE
					Id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':fname', $this->fname);
		$stmt->bindParam(':lname', $this->lname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':id', $this->id);

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
