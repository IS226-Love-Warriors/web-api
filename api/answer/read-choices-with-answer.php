<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/answer.php';
include_once '../object/question.php';
include_once '../object/examination.php';
include_once '../object/result.php';

$data = json_decode(file_get_contents("php://input"));

$database = new Database();
$db = $database->getConnection();

$questions = new Answer($db);
$questionText = new Question($db);
$examination = new Examination($db);
$results = new Result($db);

$questionText->exam_id = $data->exam_id;
$stmt = $questionText->readByExamId();
$num = $stmt->rowCount();

$questions_arr=array();
$questions_arr["records"]=array();
$exam_arr=array();

if($num > 0){
    $examination->exam_id =  $data->exam_id;
    $examination_result = $examination->readByExaminationId();
    while($examination_row = $examination_result->fetch(PDO::FETCH_ASSOC)){
        extract($examination_row);
        $exam_arr["exam_id"] = $exam_id;
        $exam_arr["exam_text"] = $exam_desc;
        $exam_arr["exam_date"] = $exam_date;
    }
    
    $questions_arr["exam_details"] = $exam_arr;

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $questions->question_id = $question_id;
        $choices_stmt = $questions->getChoicesPerQuestionId();
        $choices_arr=array();
        $correct_arr = array();
        while($choices_row = $choices_stmt->fetch(PDO::FETCH_ASSOC)){
            extract($choices_row);
            if($is_correct == 1){
                $correct_item = array(
                    "choice_id" => $answer_id,
                    "choice_text" => $answer_text,
                    "seq_no" => $seq_no
                );
            }
            $choices_item = array(
                "choice_id" => $answer_id,
                "choice_text" => $answer_text,
                "seq_no" => $seq_no
            );

            array_push($choices_arr, $choices_item);
        }

        $question_item = array(
            "question_id" => $question_id,
            "question_text" => $question_text,
            "choices" => $choices_arr,
            "correct_answer" => $correct_item
        );

        array_push($questions_arr["records"], $question_item);
        

    }

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