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
  
$answers = new Answer($db);

$data = json_decode(file_get_contents("php://input"));
    if(!empty($data)){
        $questions->question_id = $data->question_id;
        $ques_stmt = $questions->readByQuestionId();

        while ($quest_row = $ques_stmt->fetch(PDO::FETCH_ASSOC)){
            extract($quest_row);
            $questions->question_type = (!empty($data->question_type)) ? $data->question_type : $question_type;
            $questions->question_text = (!empty($data->question_text)) ? $data->question_text : $question_text;

        }
        http_response_code(201);
        echo json_encode(array("code" => "Ok","message" => "Record updated"));
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create examination. Data is incomplete."));
}
?>