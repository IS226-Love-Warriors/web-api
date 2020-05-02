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
                " . $this->table_name . " (student_id, subject_id, grading_period, criteria_id, criteria_name, score, no_of_items, percentage, score_equivalent) 
                VALUES (:student_id, :subject_id, :grading_period, :criteria_id, :criteria_name, :score, :no_of_items, :percentage, :score_equivalent) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":student_id", $this->student_id);
        $stmt->bindParam(":subject_id", $this->subject_id);
        $stmt->bindParam(":grading_period", $this->grading_period);
        $stmt->bindParam(":criteria_id", $this->criteria_id);
        $stmt->bindParam(":criteria_name", $this->criteria_name);
        $stmt->bindParam(":score", $this->score);
        $stmt->bindParam(":no_of_items", $this->no_of_items);
        $stmt->bindParam(":percentage", $this->percentage);
        $stmt->bindParam(":score_equivalent", $this->score_equivalent);
        
        $stmt->execute();
        return $stmt;  
    }

    // update student_subject_grade
    function ssgUpdate(){
        
        $query = "UPDATE student_subject_grade SET score = '". $this->score ."', score_equivalent = ((2 / no_of_items) * 100 ) * (percentage / 100)
        WHERE criteria_id ='". $this->criteria_id ."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;  
    }
    function getAllStudent(){
        $query = "SELECT ssg.student_id, u.first_name, u.last_name, u.grade_year_level
        FROM student_subject_grade ssg
        JOIN users u ON ssg.student_id = u.user_id
        GROUP BY student_id;";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;

    }

    function getAllStudentWithGradesPerSubject(){
        $query = "SELECT ssg.student_id,u.first_name, u.last_name, ssg.grading_period, ssg.subject_id, s.subject_name, s.assigned_teacher, sum(score_equivalent) as grade 
        FROM student_subject_grade ssg
        JOIN users u ON ssg.student_id = u.user_id
        JOIN subjects s ON ssg.subject_id = s.subject_id
        WHERE ssg.student_id = '". $this->student_id ."'
        GROUP BY subject_id, student_id, grading_period";

        
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    function getGradesBreakdown(){
        $query = "SELECT ssg.student_id,u.first_name, u.last_name, ssg.grading_period, ssg.subject_id, s.subject_name, ssg.criteria_id, ssg.criteria_name, ssg.score, ssg.percentage, ssg.score_equivalent 
        FROM student_subject_grade ssg
        JOIN users u ON ssg.student_id = u.user_id
        JOIN subjects s ON ssg.subject_id = s.subject_id
        WHERE ssg.student_id = '". $this->student_id ."' AND ssg.subject_id = '". $this->subject_id ."'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;

    }
}
?>
