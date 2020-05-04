<?php
class Examination{
  
    // database connection and table name
    private $conn;
    private $table_name = "examinations";
  
    // object properties
    public $id;

     // constructor with $db as database connection
     public function __construct($db){
        $this->conn = $db;
    }

    // create product
    function createExam(){
        $q = "INSERT INTO
                " . $this->table_name . " (grading_period, exam_id, subject_id, exam_date, exam_desc, criteria_id) 
                VALUES (:grading_period, :exam_id, :subject_id, :exam_date, :exam_desc, :criteria_id) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":grading_period", $this->grading_period);
        $stmt->bindParam(":exam_id", $this->exam_id);
        $stmt->bindParam(":subject_id", $this->subject_id);
        $stmt->bindParam(":exam_date", $this->exam_date);
        $stmt->bindParam(":exam_desc", $this->exam_desc);
        $stmt->bindParam(":criteria_id", $this->criteria_id);
        
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

    function readByExaminationId(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE exam_id='" . $this->exam_id . "'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    function examUpdate(){
        
        $query = "UPDATE examinations SET exam_date = '". $this->exam_date ."', exam_desc = '". $this->exam_desc ."', criteria_id = '". $this->criteria_id ."'
        WHERE exam_id ='". $this->exam_id ."'";
        // prepare query
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;  
    }

}
?>
