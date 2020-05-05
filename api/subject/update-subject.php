<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/subject.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$subject = new Examination($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data)){
    $subject->subject_id = $data->subject_id;
    $subject_stmt = $subject->readBySubjId();

    while ($subject_row = $subject_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($subject_row);
        $subject->subject_name = (!empty($data->subject_name)) ? $data->subject_name : $subject_name;
        $subject->is_active = (!empty($data->is_active)) ? 0 : $is_active;
        $subject->subjectUpdate();
    }

    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Record updated"));
}
else{
    // set response code - 400 bad request
    http_response_code(403);
    // tell the user
    echo json_encode(array("message" => "Identifier cannot be emptied"));
}
?>