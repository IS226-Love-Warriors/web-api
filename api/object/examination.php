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
                " . $this->table_name . " (exam_id, subject_id, exam_date, exam_desc) 
                VALUES (:exam_id, :subject_id, :exam_date, :exam_desc) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":exam_id", $this->exam_id);
        $stmt->bindParam(":subject_id", $this->subject_id);
        $stmt->bindParam(":exam_date", $this->exam_date);
        $stmt->bindParam(":exam_desc", $this->exam_desc);
        
        $stmt->execute();
        return $stmt;
        
    }
}

class Question{
  
    // database connection and table name
    private $conn;
    private $table_name = "exams_questions";
  
    // object properties
    public $id;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
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
}

class Answer{
  
    // database connection and table name
    private $conn;
    private $table_name = "exam_answer_choices";
  
    // object properties
    public $id;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create product
    function createExamAnswers(){
        $q = "INSERT INTO
                " . $this->table_name . " (answer_id, question_id, answer_text, seq_no, is_correct) 
                VALUES (:answer_id, :question_id, :answer_text, :seq_no, :is_correct) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":answer_id", $this->answer_id);
        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":answer_text", $this->answer_text);
        $stmt->bindParam(":seq_no", $this->seq_no);
        $stmt->bindParam(":is_correct", $this->is_correct);
        
        $stmt->execute();
        return $stmt;
        
    }
}
?>
