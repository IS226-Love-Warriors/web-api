<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/examination.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$exam = new Examination($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data)){
    $exam->exam_id = $data->exam_id;
    $exam_stmt = $exam->readByExaminationId();

    while ($exam_row = $exam_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($exam_row);
        $exam->exam_date = (!empty($data->exam_date)) ? $data->exam_date : $exam_date;
        $exam->exam_desc = (!empty($data->exam_desc)) ? $data->exam_desc : $exam_desc;
        $exam->criteria_id = (!empty($data->criteria_id)) ? $data->criteria_id : $criteria_id;
        $exam->is_active = (!empty($data->is_active)) ? 0 : $is_active;
        $exam->examUpdate();
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