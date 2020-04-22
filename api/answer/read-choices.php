<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/answer.php';
include_once '../object/question.php';
include_once '../object/examination.php';

$data = json_decode(file_get_contents("php://input"));

$database = new Database();
$db = $database->getConnection();

$questions = new Answer($db);
$questionText = new Question($db);
$examination = new Examination($db);

$questions->exam_id = $data->exam_id;
$stmt = $questions->getQuestionsPerExam();
$num = $stmt->rowCount();

if($num > 0){
    $questions_arr=array();
    $quest_arr=array();
    $exam_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $question_item["answer_id"] = $answer_id;

            $questionText->question_id = $question_id;
            $result = $questionText->readByQuestionId();
            while($result_row = $result->fetch(PDO::FETCH_ASSOC)){
                extract($result_row);
                $quest_arr["question_id"] = $question_id;
                $quest_arr["question_text"] = $question_text;
            }

            $examination->exam_id = $exam_id;
            $examination_result = $examination->readByExaminationId();
            while($examination_row = $examination_result->fetch(PDO::FETCH_ASSOC)){
                extract($examination_row);
                $exam_arr["exam_id"] = $exam_id;
                $exam_arr["exam_text"] = $exam_desc;
            }

        $question_item["examination"] = $exam_arr;
        $question_item["question"] = $quest_arr;        
        $question_item["answer_text"] = $answer_text;
        $question_item["seq_no"] = $seq_no;
        $question_item["is_correct"] = $is_correct;
        array_push($questions_arr, $question_item);
    }

    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Record fetched","data" => $questions_arr));
}
else{
    http_response_code(200);
    echo json_encode(array("code" => "Conflict", "message" => "No record found", "data"=>"{}"));
}


?>