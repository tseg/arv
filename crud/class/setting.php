<?php

class Setting {

    // database connection and table name
    private $conn;
    private $table_name = "tbl_setting";
    
    public $Id;
    public $Place_Date_Time;
    public $Exit_Date_Time;
    public $Investment;
    public $Judge_Deadline;
    public $Upload_Deadline;
    public $Notification_Time;
    public $projectId;

    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        
        try {
            $query = "INSERT INTO ". $this->table_name ." values('',?,?,?,?,?,?,?)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->Judge_Deadline);
            $stmt->bindParam(2, $this->Upload_Deadline);
            $stmt->bindParam(3, $this->Notification_Time);
            $stmt->bindParam(4, $this->Exit_Date_Time);
            $stmt->bindParam(5, $this->Place_Date_Time);
            $stmt->bindParam(6, $this->Investment);
            $stmt->bindParam(7, $this->projectId);
            
            $stmt->execute();
            
            return true;
        } catch (Exception $ex) {
            return false;
            
        }
    }

    public function countAll() {

        $query = "SELECT * FROM " . $this->table_name . " ";

        $stmt = $this->conn->prepare($query);
        $stmt->excuete($stmt);

        $num = $stmt->rowCount();
        return $num;
    }

    public function readOne() {

        $query = "SELECT * FROM " . $this->table_name . " WHERE Judge_Deadline = ? and Upload_Deadline = ? and Notification_Time = ? and Exit_Date_Time = ? and Place_Date_Time = ? and Investment = ?  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $this->Judge_Deadline);
        $stmt->bindParam(2, $this->Upload_Deadline);
        $stmt->bindParam(3, $this->Notification_Time);
        $stmt->bindParam(4, $this->Exit_Date_Time);
        $stmt->bindParam(5, $this->Place_Date_Time);
        $stmt->bindParam(6, $this->Investment);
            
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(count($row) == 0){
            return true;
        }else{
            $this->Id = $row['Id'];
            $this->Judge_Deadline = $row['Judge_Deadline'];
            $this->Upload_Deadline = $row['Upload_Deadline'];
            $this->Notification_Time = $row['Notification_Time'];
            $this->Exit_Date_Time = $row['Exit_Date_Time'];
            $this->Place_Date_Time = $row['Place_Date_Time'];
            $this->Investment = $row['Investment'];
        }
    }

    public function update() {
        $query = "UPDATE
                     " . $this->table_name . "
			SET
			    Place_Date_Time = :pdate,
                Exit_Date_Time = :edate,
                Judge_Deadline = :investment,
                Upload_Deadline = :item
                Investment = :investment
			WHERE
                            Id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':pdate', $this->Place_Date_Time);
        $stmt->bindParam(':edate', $this->Exit_Date_Time);
        $stmt->bindParam(':investment', $this->Investment);
        $stmt->bindParam(':item', $this->Item);
        $stmt->bindParam(':id', $this->Id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    function delete() {

        $query = "DELETE FROM " . $this->table_name . " WHERE Id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

}
