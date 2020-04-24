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
                " . $this->table_name . " (criteria_id, criteria_text, percentage) 
                VALUES (:criteria_id, :criteria_text, :percentage) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":criteria_id", $this->ancriteria_idswer_id);
        $stmt->bindParam(":criteria_text", $this->criteria_text);
        $stmt->bindParam(":percentage", $this->percentage);
        
        $stmt->execute();
        return $stmt; 
    }

    function read(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . "";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
}
?>