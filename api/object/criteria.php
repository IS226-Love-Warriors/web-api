<?php
class Criteria{
    // database connection and table name
    private $conn;
    private $table_name = "criteria";
  
    // object properties
    public $id;

     // constructor with $db as database connection
     public function __construct($db){
        $this->conn = $db;
    }

    function createCriteria(){
        $q = "INSERT INTO
                " . $this->table_name . " (subject_id, criteria_id, criteria_name, percentage) 
                VALUES (:subject_id, :criteria_id, :criteria_name, :percentage) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":subject_id", $this->subject_id);
        $stmt->bindParam(":criteria_id", $this->criteria_id);
        $stmt->bindParam(":criteria_name", $this->criteria_name);
        $stmt->bindParam(":percentage", $this->percentage);
        $stmt->execute();
        return $stmt; 
    }

    function readLast(){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY ID DESC LIMIT 1";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readBySubjectId(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE subject_id='" . $this->subject_id . "'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    function readAll(){
        //SELECT DISTINCT Country FROM Customers;
        $query = "SELECT DISTINCT criteria_id, criteria_name FROM " . $this->table_name;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
}
?>