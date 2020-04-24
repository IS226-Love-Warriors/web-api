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
                " . $this->table_name . " (criteria_id, criteria_name, criteria_item, percentage) 
                VALUES (:criteria_id, :criteria_name, :criteria_item, :percentage) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":criteria_id", $this->criteria_id);
        $stmt->bindParam(":criteria_name", $this->criteria_name);
        $stmt->bindParam(":criteria_item", $this->criteria_item);
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