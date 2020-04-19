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

// get posted data
$data = json_decode(file_get_contents("php://input"));
    // make sure data is not empty
    if(!empty($data)){
    // set user property values
    $exams->exam_id = uniqid('exam_');
    $exams->grading_period = $data->grading_period;
    $exams->subject_id = $data->subject_id;
    $exams->exam_date = $data->exam_date;
    $exams->exam_desc = $data->exam_desc;

    // create the user
    if($exams->createExam()){
        // set response code - 201 created
        http_response_code(201);
        // tell the subject
        echo json_encode(array("message" => "Examination was created."));
    }
  
    // if unable to create the subject, tell the subject
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create examination."));
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