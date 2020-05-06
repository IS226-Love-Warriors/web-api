<?php
class Subject{
  
    // database connection and table name
    private $conn;
    private $table_name = "subjects";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    function readLast(){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY ID DESC LIMIT 1";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
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

    function readByLevel(){
        $query = "SELECT subject_id, subject_name, first_name, last_name FROM subjects join users on subjects.assigned_teacher = users.user_id WHERE grade_year = '" . $this->grade_year ."' AND subjects.is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readByLevelTeacher(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE grade_year='" . $this->grade_year . "'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readByAssignedTeacher(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE assigned_teacher='" . $this->assigned_teacher . "' AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
       //get one record
    function readBySubjId(){
        $query = "SELECT * FROM SUBJECTS join USERS on SUBJECTS.`assigned_teacher` = USERS.`user_id` WHERE SUBJECTS.subject_id = '" . $this->subject_id . "' AND SUBJECTS.is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readDistinctBySubjId(){
        $query = "SELECT DISTINCT subject_id, subject_name FROM " . $this->table_name . " WHERE subject_id='" . $this->subject_id . "'";
        $stmt = $this->conn->prepare($query);
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

    function subjectUpdate(){
        $query = "UPDATE subjects SET subject_name = '". $this->subject_name ."'
        WHERE user_id ='". $this->user_id ."'";
        // prepare query
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;  
    }
}
?>
