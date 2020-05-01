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
include_once '../object/student_subject_grade.php';


// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$results = new Result($db);
$answers = new Answer($db);
$grades = new StudentSubjectGrade($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
    // make sure data is not empty
    if(!empty($data)){
    $score = 0;
    $items = 0;
    // set question property values

    for($x = 0; $x < count($data->answers); $x++){
        $results->result_id = uniqid('result_');
        $results->student_id = $data->student_id;
        $results->exam_id = $data->exam_id;
        $results->question_id = $data->answers[$x]->question_id;
        $results->seq_no = $data->answers[$x]->seq_no;
        $results->stud_answer_text = $data->answers[$x]->stud_answer_text;

        $answers->question_id = $data->answers[$x]->question_id;
        $stmts = $answers->getCorrectAnswer();
        $nums = $stmts->rowCount();

        while ($row = $stmts->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $results->correct_answer_text = $answer_text;
            if($data->answers[$x]->stud_answer_text == $answer_text){
                $results->is_correct = 1;
                $score = $score + 1;
            }
            else{
                $results->is_correct = 0;
            } 

            $items = $items + 1;
        }
        $results->createQuestionAnswers();
    }
        $results->exam_id = $data->exam_id;
        $result_stmt =  $results->readExamCriteria();
        while ($result_row = $result_stmt->fetch(PDO::FETCH_ASSOC)){
            extract($result_row);
            $grades->student_id = $data->student_id;
            $grades->subject_id = $subject_id;
            $grades->grading_period = $grading_period;
            $grades->criteria_id = $criteria_id;
            $grades->criteria_name = $criteria_name;
            $grades->score = $score;
            $grades->no_of_items = $items;
            $grades->percentage = $percentage;
            $grades->score_equivalent =  (($score / $items) * 100) * ($percentage/100);
            $grades->ssgCreate();
        }

        $response["exam_id"] = $data->exam_id;
        if($score == 0){
            $response["score"] = 0;
            $response["percentage"] =  "0.00 %";
        }
        $response["score"] = $score . "/" . $items;
        $response["percentage"] = (($score / $items) * 100) . "%";

        http_response_code(201);
        echo json_encode(array("code"=>"Ok", "message" => "You successfully finished the examination", "data" => $response));

}
// tell the exam data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
    // tell the subject
    echo json_encode(array("message" => "Unable to create examination. Data is incomplete."));
}
?>