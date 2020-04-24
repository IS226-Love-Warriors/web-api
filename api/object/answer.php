<?php
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

    function createQuestionAnswers(){
        $q = "INSERT INTO
                " . $this->table_name . " (answer_id, exam_id, question_id, seq_no, answer_text, is_correct) 
                VALUES (:answer_id, :exam_id, :question_id, :seq_no, :answer_text, :is_correct) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":answer_id", $this->answer_id);
        $stmt->bindParam(":exam_id", $this->exam_id);
        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":seq_no", $this->seq_no);
        $stmt->bindParam(":answer_text", $this->answer_text);
        $stmt->bindParam(":is_correct", $this->is_correct);
        
        $stmt->execute();
        return $stmt; 
    }

    function getQuestionsPerExam(){
         // select all query
         $query = "SELECT * FROM " . $this->table_name . " WHERE exam_id ='" . $this->exam_id . "'";
    
         // prepare query statement
         $stmt = $this->conn->prepare($query);
 
         // execute query
         $stmt->execute();
         return $stmt;
    }

    function getChoicesPerQuestionId(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE question_id ='" . $this->question_id . "'";
   
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
   }

    function getCorrectAnswer(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE question_id ='" . $this->question_id . "' AND is_correct = 1" ;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    function getChoicesPerQuestion(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE answer_id ='" . $this->answer_id . "'" ;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

}
?>
