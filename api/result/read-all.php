<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/result.php';
include_once '../object/user.php';
include_once '../object/examination.php';
include_once '../object/question.php';
include_once '../object/answer.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$result = new Result($db);
$student = new User($db);
$exam = new Examination($db);
$question = new Question($db);
$choices = new Answer($db);

$data = json_decode(file_get_contents("php://input"));
$result->student_id = $data->student_id;
$result->exam_id = $data->exam_id;
$stmt = $result->readByStudentAndExam();
$num = $stmt->rowCount();
// check if more than 0 record found
if($num>0){
    $results_arr=array();
    $student_arr=[];
    $exam_arr = [];
    $question_arr = [];
    $choices_arr = [];
    $result_item =[];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $result_item["result_id"] = $result_id;
            $student->user_id = $student_id;
            $student_stmt = $student->readOneById();
            while ($student_row = $student_stmt->fetch(PDO::FETCH_ASSOC)){
                extract($student_row);
                $student_arr["student_id"] = $student_id;
                $student_arr["name"] = $first_name . " " . $last_name;
            }

            $exam->exam_id = $exam_id;
            $exam_stmt = $exam->readByExaminationId();
            while ($exam_row = $exam_stmt->fetch(PDO::FETCH_ASSOC)){
                extract($exam_row);
                $exam_arr["exam_id"] = $exam_id;
                $exam_arr["exam_desc"] = $exam_desc;
            }

            $question->question_id = $question_id;
            $question_stmt = $question->readByQuestionId();
            while ($question_row = $question_stmt->fetch(PDO::FETCH_ASSOC)){
                extract($question_row);
                $question_arr["question_id"] = $question_id;
                $question_arr["question_text"] = $question_text;
            }

            $choices->answer_id = $stud_answer_id;
            $choices_stmt = $choices->getChoicesPerQuestion();
            while ($choices_row = $choices_stmt->fetch(PDO::FETCH_ASSOC)){
                extract($choices_row);
                $choices_arr["student_answer_id"] = $stud_answer_id;
                $choices_arr["student_answer_text"] = $answer_text;    
            }

            $choices->question_id= $question_id;
            $answer_stmt = $choices->getCorrectAnswer();
            while ($answer_row = $answer_stmt->fetch(PDO::FETCH_ASSOC)){
                extract($answer_row);
                $choices_arr["correct_answer_id"] = $answer_id;
                $choices_arr["correct_answer_text"] = $answer_text;    
            }
        $result_item["student_details"] = $student_arr;
        $result_item["exam_details"] = $exam_arr;
        $result_item["question_details"] = $question_arr;
        $result_item["answer_details"] = $choices_arr;
        $result_item["seq_no"] = $seq_no;
        $result_item["is_correct"] = $is_correct;

        array_push($results_arr, $result_item);   
    } 
    
    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Records fetched", "data" => $results_arr));

} else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No records found.")
    );
}

?>