<?php
class Subject{
  
    // database connection and table name
    private $conn;
    private $table_name = "subjects";
  
    // object properties
    public $id;
    public $subject_id;
    public $subject_name;
    public $level;
    public $grade_year;
    public $acad_year;
    public $assigned_teacher;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . "";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByLevel(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE grade_year='" . $this->grade_year . "'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

       //get one record
       function readBySubjId(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE subject_id='" . $this->subject_id . "'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create product
    function create(){
        $q = "INSERT INTO
                " . $this->table_name . " (subject_id, subject_name, grade_year, level, acad_year, assigned_teacher) 
                VALUES (:subject_id, :subject_name, :grade_year, :level, :acad_year, :assigned_teacher) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":subject_id", $this->subject_id);
        $stmt->bindParam(":subject_name", $this->subject_name);
        $stmt->bindParam(":level", $this->level);
        $stmt->bindParam(":grade_year", $this->grade_year);
        $stmt->bindParam(":acad_year", $this->acad_year);
        $stmt->bindParam(":assigned_teacher", $this->assigned_teacher);
        
        $stmt->execute();
        return $stmt;
        
    }
}
?>
