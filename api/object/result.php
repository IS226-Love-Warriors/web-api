<?php
class Result{
  
    // database connection and table name
    private $conn;
    private $table_name = "answer_results";
  
    // object properties
    public $id;

     // constructor with $db as database connection
     public function __construct($db){
        $this->conn = $db;
    }

    function createQuestionAnswers(){
        $q = "INSERT INTO
                " . $this->table_name . " (result_id, student_id, exam_id, question_id, seq_no, stud_answer_id, answer_id, is_correct) 
                VALUES (:result_id, :student_id, :exam_id, :question_id, :seq_no, :stud_answer_id, :answer_id, :is_correct) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        
        // bind values
        $stmt->bindParam(":result_id", $this->result_id);
        $stmt->bindParam(":student_id", $this->student_id);
        $stmt->bindParam(":exam_id", $this->exam_id);
        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":seq_no", $this->seq_no);
        $stmt->bindParam(":stud_answer_id", $this->stud_answer_id);
        $stmt->bindParam(":answer_id", $this->answer_id);
        $stmt->bindParam(":is_correct", $this->is_correct);
        
        $stmt->execute();
        return $stmt;
        
    }

        // read users
        function readByStudentAndExam(){
            // select all query
            $query = "SELECT * FROM " . $this->table_name . " WHERE student_id='" . $this->student_id . "' AND exam_id='" .$this->exam_id . "'";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
            return $stmt;
        }
}
?>
