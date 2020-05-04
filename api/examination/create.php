<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/examination.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$exams = new Examination($db);

$data = json_decode(file_get_contents("php://input"));
    if(!empty($data)){
    $exam_stmt = $exams->read();
    $exam_count = $exam_stmt->rowCount();

    if($exam_count>0){
        while ($exam_row = $exam_stmt->fetch(PDO::FETCH_ASSOC)){
            extract($exam_row);
            $exams->exam_id = "exam_2019_2020_1" . ( $id + 5);
        }
    } else{
        $exams->exam_id = "exam_2019_2020_1";
    }
    
    $exams->grading_period = $data->grading_period;
    $exams->subject_id = $data->subject_id;
    $exams->exam_date = $data->exam_date;
    $exams->exam_desc = $data->exam_desc;
    $exams->criteria_id = $data->criteria_id;

    $exams->createExam();
    $exam["exam_id"] = $exams->exam_id;
    http_response_code(201);
    echo json_encode(array("code" => "Ok", "message" => "Examination was created.", "data" => $exam));
}
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the subject
    echo json_encode(array("message" => "Unable to create examination. Data is incomplete."));
}
?>