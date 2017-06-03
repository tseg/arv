<?php

class Data
{

    // database connection and table name
    private $conn;
    private $table_name = "crudpdo";

    // object properties
    public $id;
    public $name;
    public $gender;
    public $contactNum;
    public $address;

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
        $stmt->bindParam(2, $this->gender);
        $stmt->bindParam(3, $this->contactNum);
        $stmt->bindParam(4, $this->address);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    }

    // read products
    function readAll($page, $from_record_num, $records_per_page)
    {

        $query = "SELECT * FROM  " . $this->table_name . " ORDER BY nm_pdo ASC LIMIT {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // used for paging products
    public function countAll()
    {

        $query = "SELECT id_pdo FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }

    // used when filling up the update product form
    function readOne()
    {

        $query = "SELECT * FROM  " . $this->table_name . " WHERE id_pdo = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['nm_pdo'];
        $this->gender = $row['gd_pdo'];
        $this->contactNum = $row['tl_pdo'];
        $this->address = $row['ar_pdo'];
    }

    // update the product
    function update()
    {


        $query = "UPDATE
					" . $this->table_name . "
				SET
					nm_pdo = :nm,
					gd_pdo = :gd,
					tl_pdo = :tl,
					ar_pdo = :ar
				WHERE
					id_pdo = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nm', $this->name);
        $stmt->bindParam(':gd', $this->gender);
        $stmt->bindParam(':tl', $this->contactNum);
        $stmt->bindParam(':ar', $this->address);
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

        $query = "DELETE FROM " . $this->table_name . " WHERE id_pdo = ?";

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
