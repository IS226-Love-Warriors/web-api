<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/student_subject_grade.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$student_subject_grade = new StudentSubjectGrade($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data)){
    $no_passed_value = 0;
    $student_subject_grade->student_id = $data->student_id;
    $student_subject_grade->subject_id = $data->subject_id;
    $student_subject_grade->grading_period = $data->grading_period;
    $student_subject_grade->assignment = !empty($data->assignment) ? $data->assignment:$no_passed_value;
    $student_subject_grade->class_work = !empty($data->class_work) ? $data->class_work : $no_passed_value;
    $student_subject_grade->labs_projects = !empty($data->labs_projects) ? $data->labs_projects : $no_passed_value;
    $student_subject_grade->work_book = !empty($data->work_book) ? $data->work_book : $no_passed_value;
    $student_subject_grade->ssgUpdate();

    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Record updated"));
}

?>