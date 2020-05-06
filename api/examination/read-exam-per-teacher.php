<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/examination.php';
include_once '../object/subject.php';
include_once '../object/user.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$exam = new Examination($db);

$data = json_decode(file_get_contents("php://input"));
$exam->teacher_id = $data->teacher_id;
$exam_stmt = $exam->readByTeacherId();
while ($exam_row = $exam_stmt->fetch(PDO::FETCH_ASSOC)){
    extract($exam_row);
    
}
http_response_code(201);
echo json_encode(array("code"=>"Ok", "message" => "Record fetched", "data"=> $exams_arr));


http_response_code(200);
echo json_encode(array("code" => "Ok", "message" => "No examination found."));

?>