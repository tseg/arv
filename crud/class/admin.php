<?php

class Group
{

    // database connection and table name
    private $conn;
    private $table_name = "tbl_group";

    // object properties
    public $id;
    public $name;
    public $description;
    public $status;
	public $created;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create product
    function create()
    {

        //write query
        $query = "INSERT INTO " . $this->table_name . " values('',?,?,?,?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->description);
        $stmt->bindParam(3, $this->status);
        $stmt->bindParam(4, $this->created);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    }

    // read products
    function readAll($page, $from_record_num, $records_per_page)
    {

        $query = "SELECT * FROM  " . $this->table_name . " ORDER BY Name ASC LIMIT {$from_record_num}, {$records_per_page}";

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

        $query = "SELECT * FROM  " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['Name'];
        $this->description = $row['Description'];
        $this->status = $row['Status'];
        $this->created = $row['Created_Date'];
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

    // delete the product
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
}

?>
