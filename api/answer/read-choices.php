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

$questions_arr=array();
$questions_arr["records"]=array();
$quest_arr=array();
$exam_arr=array();

if($num > 0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
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
                $exam_arr["exam_date"] = $exam_date;
            }

        $question_item=array(
            "answer_id" => $answer_id,
            "question" => $quest_arr,
            "answer_text" => $answer_text,
            "seq_no" => $seq_no,
            "is_correct" => $is_correct
        );
        array_push($questions_arr["records"], $question_item);
    }
    $questions_arr["exam_details"] = $exam_arr;

    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Record fetched","data" => $questions_arr ));
}
else{
    $examination->exam_id = $data->exam_id;
    $examination_result = $examination->readByExaminationId();
    while($examination_row = $examination_result->fetch(PDO::FETCH_ASSOC)){
        extract($examination_row);
        $exam_arr["exam_id"] = $exam_id;
        $exam_arr["exam_text"] = $exam_desc;
        $exam_arr["exam_date"] = $exam_date;
    }
    $questions_arr["exam_details"] = $exam_arr;
    http_response_code(200);
    echo json_encode(array("code" => "Conflict", "message" => "No record found", "data"=>$questions_arr));
}


?>