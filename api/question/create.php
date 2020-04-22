<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/question.php';
include_once '../object/answer.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$questions = new Question($db);
$answers = new Answer($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
    // make sure data is not empty
    if(!empty($data)){
    // set user property values
    $questions->question_id = uniqid('question_');
    $questions->exam_id = $data->exam_id;
    $questions->question_type = $data->question_type;
    $questions->question_text = $data->question_text;

    // create the question
    if($questions->createExamQuestions()){
        // set question property values
        $answers->question_id = $questions->question_id;
        for($x = 0; $x < count($data->choices); $x++){
            $answers->answer_id = uniqid('answer_');
            $answers->exam_id = $data->exam_id;
            $answers->answer_text = $data->choices[$x]->answer_text;
            $answers->seq_no = $data->choices[$x]->seq_no;
            $answers->is_correct = $data->choices[$x]->is_correct;

            $answers->createQuestionAnswers();

        }

        unset($questions->id);
        http_response_code(201);
        echo json_encode(array("code" => "Ok","message" => "Item added.", "data"=>$questions));
        
    }
    // if unable to create the subject, tell the subject
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to add an item."));
    }
}
// tell the exam data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
    // tell the subject
    echo json_encode(array("message" => "Unable to create examination. Data is incomplete."));
}
?>