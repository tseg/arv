<?php

class ViewerGroup
{

    // database connection and table name
    private $conn;
    private $table_name = "tbl_viewer_group";

    // object properties
    public $id;
    public $viewer_id;
    public $project_id;
    public $created;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET Viewer_Id = ?, Project_Id = ?, Date = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->viewer_id);
        $stmt->bindParam(2, $this->project_id);
        $stmt->bindParam(3, $this->created);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    }

    function readAll($page, $from_record_num, $records_per_page)
    {

        $query = "SELECT * FROM  " . $this->table_name . " ORDER BY User_Name ASC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // used for paging products
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
		/*
		*TO DO: add where criteria using user_id and user_type
		*/
        $query = "SELECT * FROM  " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->user_name = $row['User_Name'];
        $this->user_id = $row['User_Id'];
        $this->user_type = $row['User_Type'];
        $this->status = $row['Status'];
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

    // update the product
    function update()
    {


        $query = "UPDATE
					" . $this->table_name . "
				SET
					Name = :name,
					Description = :description,
					Status = :status
				WHERE
					Id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
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
    }
	
	function delete_by_viewer()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE Project_Id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->project_id);

        if ($result = $stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>
