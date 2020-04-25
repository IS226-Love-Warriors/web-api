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
$answer_arr = [];
$response_arr = array();

$data = json_decode(file_get_contents("php://input"));
    if(!empty($data)){
        $questions->exam_id = $data->exam_id;
        $questions->question_type = $data->question_type;

        for($x = 0; $x < count($data->questions); $x++){
            $question_stmt = $questions->readLast();
            $row_num = $question_stmt->rowCount();

            if($row_num == 0){
                $questions->question_id = "question_2019_2020_1";
            }
            else{
                while ($question_row = $question_stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($question_row); 
                    $questions->question_id = "question_2019_2020_" . ( $id + 5);
                }
            }
            $questions->question_text = $data->questions[$x]->question_name;
            $questions->createExamQuestions();
            
            for($y = 0; $y < count($data->questions[$x]->choices); $y++){

                $answer_stmt = $answers->readLast();
                $answer_row_count = $answer_stmt->rowCount();
        
                if($answer_row_count == 0){
                    $answers->answer_id = "answer_2019_2020_1";
                }
                else{
                    while ($answer_row = $answer_stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($answer_row); 
                        $answers->answer_id = "answer_2019_2020_" . ( $id + 5);
                    }
                }

                $answers->exam_id = $data->exam_id;
                $answers->question_id = $questions->question_id;
                $answers->answer_text = $data->questions[$x]->choices[$y]->text;
                $answers->seq_no = $data->questions[$x]->choices[$y]->key;
                if($data->questions[$x]->choices[$y]->key == $data->questions[$x]->answer){
                    $answers->is_correct = 1;
                }else{
                    $answers->is_correct = 0;
                }
                $answers->createQuestionAnswers();
            }

        }

        http_response_code(201);
        echo json_encode(array("code" => "Ok","message" => "Item added.", "data"=>$answers));
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create examination. Data is incomplete."));
}
?>