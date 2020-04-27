<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/result.php';
include_once '../object/user.php';
include_once '../object/examination.php';
include_once '../object/question.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$results = new Result($db); 
$user = new User($db);
$exam = new Examination($db);
$question = new Question($db);

$data = json_decode(file_get_contents("php://input"));
$result_arr = array();
$result_arr["exam_details"] = array();

if(!empty($data)){
    $scores = 0;
    $items = 0;
    $results->exam_id = $data->exam_id;
    $results->student_id = $data->student_id;
    $result_stmt = $results->readByStudentAndExam();
    while ($result_row = $result_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($result_row);

        $question->question_id = $question_id;
        $question_stmt = $question->readByQuestionId();
        while ($question_row = $question_stmt->fetch(PDO::FETCH_ASSOC)){
            extract($question_row);
            $result_item = array(
                "question_id"=>$question_id,
                "question_text" => $question_text,
                "stud_answer_text"=>$stud_answer_text,
                "correct_answer_text"=>$correct_answer_text,
                "is_correct" => $is_correct
            );
        }
        
        array_push($result_arr["exam_details"], $result_item);
        if($is_correct == 1){
            $scores = $scores + 1;
            $items = $items + 1;
        }
        else{
            $items = $items + 1;
        }
    }

    $user->user_id = $data->student_id;
    $user_stmt = $user->readOneById();
    while ($user_row = $user_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($user_row);
        $result_arr["student_id"] = $data->student_id;
        $result_arr["name"] = $first_name . " " . $last_name;
    }

    $exam->exam_id = $data->exam_id;
    $exam_stmt = $exam->readByExaminationId();
    while ($exam_row = $exam_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($exam_row);
        $result_arr["exam_id"] = $data->exam_id;
        $result_arr["exam_desc"] = $exam_desc;
    }

    if($scores == 0){
        $result_arr["score"] = 0;
        $result_arr["percentage"] =  "0.00 %";
    }
    $result_arr["score"] = $scores . "/" . $items;
    $result_arr["percentage"] = (($scores / $items) * 100) . "%";

    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Records fetched", "data" => $result_arr));
}
else{
    http_response_code(403);
    echo json_encode(array("message" => "Identifier cannot be emptied"));
}
?>