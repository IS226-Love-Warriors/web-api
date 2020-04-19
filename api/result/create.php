<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/result.php';
include_once '../object/answer.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$results = new Result($db);
$answers = new Answer($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
    // make sure data is not empty
    if(!empty($data)){
    // set question property values
    for($x = 0; $x < count($data->answers); $x++){
        $results->result_id = uniqid('result_');
        $results->student_id = $data->student_id;
        $results->exam_id = $data->exam_id;
        $results->question_id = $data->answers[$x]->question_id;
        $results->seq_no = $data->answers[$x]->seq_no;
        $results->stud_answer_id = $data->answers[$x]->stud_answer_id;

        $answers->question_id = $data->answers[$x]->question_id;
        $stmts = $answers->getCorrectAnswer();
        $nums = $stmts->rowCount();

        while ($row = $stmts->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $results->answer_id = $answer_id;
            if($data->answers[$x]->stud_answer_id == $answer_id){
                $results->is_correct = 1;
            }
            else{
                $results->is_correct = 0;
            }
              
        }

        $results->createQuestionAnswers();

    }

        http_response_code(201);
        echo json_encode(array("message" => "Item added."));

}
// tell the exam data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
    // tell the subject
    echo json_encode(array("message" => "Unable to create examination. Data is incomplete."));
}
?>