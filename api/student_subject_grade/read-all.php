<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/student_subject_grade.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$grade = new StudentSubjectGrade($db);
$data = json_decode(file_get_contents("php://input"));

$grade->student_id = $data->student_id;
$grade_stmt = $grade->readOne();
$num = $grade_stmt->rowCount();
if($num>0){
    $grade_arr = [];
    $grade_item = [];
    while($grade_row = $grade_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($grade_row);
        $grade_item["grade_id"] = "";
        $grade_item["student_id"] = $student_id;
        $grade_item["subject_id"] = $subject_id;
        $grade_item["grading_period"] = $grading_period;
        $grade_item["grade"] = $final_grade;

        array_push($grade_arr, $grade_item);  
    }

    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Records fetched", "data" => $grade_arr));
}
else{
    http_response_code(200);
    echo json_encode(
        array("message" => "No records found.")
    );
}
?>