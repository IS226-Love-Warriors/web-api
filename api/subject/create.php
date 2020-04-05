<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/subject.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$subject = new Subject($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
    // make sure data is not empty
    if(!empty($data)){
    // set user property values
    $subject->subject_id = $data->subject_id;
    $subject->subject_name = $data->subject_name;
    $subject->level = $data->level;
    $subject->grade_year = $data->grade_year;
    $subject->acad_year = $data->acad_year;
    $subject->assigned_teacher = $data->assigned_teacher;

    // create the user
    if($subject->create()){
        // set response code - 201 created
        http_response_code(201);
        // tell the subject
        echo json_encode(array("message" => "Subject was created."));
    }
  
    // if unable to create the subject, tell the subject
    else{
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the subject
        echo json_encode(array("message" => "Unable to create subject."));
    }
}
  
// tell the subject data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the subject
    echo json_encode(array("message" => "Unable to create subject. Data is incomplete."));
}
?>