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
$exam_arr = array();
$exam_arr["records"] = array();
$data = json_decode(file_get_contents("php://input"));
$exam->teacher_id = $data->teacher_id;
$exam_stmt = $exam->readByTeacherId();
$count = $exam_stmt->rowCount();

if($count>0){
    while ($exam_row = $exam_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($exam_row);
        $exam_item = array(
            "exam_id" => $exam_id,
            "grading_period" => $grading_period,
            "subject_name" => $subject_name,
            "exam_desc" => $exam_desc,
            "exam_date" => $exam_date
        );

        $teacher_item = array(
            "teacher_id" => $assigned_teacher,
            "name" => $first_name . " " .$last_name,
        );
    
        array_push($exam_arr["records"], $exam_item);
    }

    $exam_arr["assigned_teacher"] = $teacher_item;

    http_response_code(201);
    echo json_encode(array("code"=>"Ok", "message" => "Record fetched", "data"=> $exam_arr));
}
else{
    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "No examination found."));
}
?>