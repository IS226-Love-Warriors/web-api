<?php
class StudentSubjectGrade{
  
    // database connection and table name
    private $conn;
    private $table_name = "student_subject_grade";
  
    // object properties
    public $id;
    public $student_id;
    public $subject_id;
    public $final_grade;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read users
    function read(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . "";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    //get one record
    function readOne(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE student_id='" . $this->student_id . "'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create student_subject_grade
    function ssgCreate(){
        $q = "INSERT INTO
                " . $this->table_name . " (student_id, subject_id, grading_period, final_grade, assignment, class_work, labs_projects, work_book) 
                VALUES (:student_id, :subject_id, :grading_period, :final_grade, :assignment, :class_work, :labs_projects, :work_book) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        $criteria1 = 0; //times 35%
        $criteria2 = 0; //times 15%
        $criteria3 = 0; //times 25%
        $criteria4 = 0; //times 25%
 
        // bind values
        $stmt->bindParam(":student_id", $this->student_id);
        $stmt->bindParam(":subject_id", $this->subject_id);
        $stmt->bindParam(":grading_period", $this->grading_period);
        $stmt->bindParam(":final_grade", $this->final_grade);
        $stmt->bindParam(":assignment", $criteria1);
        $stmt->bindParam(":class_work", $criteria2);
        $stmt->bindParam(":labs_projects", $criteria3);
        $stmt->bindParam(":work_book", $criteria4);
        
        $stmt->execute();
        return $stmt;  
    }

    // update student_subject_grade
    function ssgUpdate(){
        
        $criteria1 = $this->assignment * 0.35; //times 35%
        $criteria2 = $this->class_work * 0.15; //times 15%
        $criteria3 = $this ->labs_projects * 0.25; //times 25%
        $criteria4 = $this->work_book * 0.25; //times 25%
        $final_grade = $criteria1 + $criteria2 + $criteria3 + $criteria4;

        $q = "UPDATE " . $this->table_name . " SET assignment=" . $this->assignment . ", class_work=" . $this->class_work . ", labs_projects =" . $this->labs_projects . ", work_book =" . $this->work_book . ", final_grade =" . $final_grade .
        "WHERE ( ( student_id = '". $this->student_id . "' AND subject_id ='" . $this->subject_id . "')) AND ( grading_period =" .$this->grading_period . " )";
        
        // prepare query
        $stmt = $this->conn->prepare($q);
        $stmt->execute();
        return $stmt;  
    }
}
?>
