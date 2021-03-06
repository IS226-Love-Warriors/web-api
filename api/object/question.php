<?php
class Question{
  
    // database connection and table name
    private $conn;
    private $table_name = "exams_questions";
  
    // object properties
    public $id;
    public $question_id;

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

    function readQuestionsAndChoices(){
        $query = "SELECT * FROM EXAM_ANSWER_CHOICES join EXAMS_QUESTIONS on EXAM_ANSWER_CHOICES.question_id = EXAMS_QUESTIONS.`question_id` WHERE EXAM_ANSWER_CHOICES.`question_id`='" . $this->question_id ."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readByExamId(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE exam_id='" . $this->exam_id . "'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    function readByQuestionId(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE question_id='" . $this->question_id . "'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create product
    function createExamQuestions(){
        $q = "INSERT INTO
                " . $this->table_name . " (question_id, exam_id, question_type, question_text) 
                VALUES (:question_id, :exam_id, :question_type, :question_text) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":exam_id", $this->exam_id);
        $stmt->bindParam(":question_type", $this->question_type);
        $stmt->bindParam(":question_text", $this->question_text);
        
        $stmt->execute();
        return $stmt;
        
    }

    function updateQuestion(){
        $query = "UPDATE exams_questions SET question_type = '". $this->question_type ."', question_text = '". $this->question_text ."', is_active = $this->is_active 
        WHERE question_id ='". $this->question_id ."'";
        // prepare query
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;  
    }
}
?>
