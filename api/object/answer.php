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
                " . $this->table_name . " (answer_id, question_id, seq_no, answer_text, is_correct) 
                VALUES (:answer_id, :question_id, :seq_no, :answer_text, :is_correct) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":answer_id", $this->answer_id);
        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":seq_no", $this->seq_no);
        $stmt->bindParam(":answer_text", $this->answer_text);
        $stmt->bindParam(":is_correct", $this->is_correct);
        
        $stmt->execute();
        return $stmt;
        
    }
}
?>
