<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/examination.php';
include_once '../object/subject.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$exam = new Examination($db);
$subject = new Subject($db);

$stmt = $exam->read();
$num = $stmt->rowCount();
// check if more than 0 record found
if($num>0){
  
    // user array
    $exams_arr=array();
    $exams_arr["exams"]=array();
  
    while ($exam_row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($exam_row);
            $subject->subject_id = $subject_id;
            $stmts = $subject->readBySubjId();
            $nums = $stmts->rowCount();

            if($nums > 0){
                while ($row = $stmts->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $exam_item=array(
                        "exam_id" => $exam_id,
                        "subject" => $subject_name,
                        "exam_date" => $exam_date,
                        "exam_desc" => $exam_desc,
                        "grade" => $grade_year, //filter for student display
                        "academic_year" => $acad_year,
                        "teacher_id" => $assigned_teacher //filter for teacher display
                    );
                }
            } else {
                http_response_code(404);
                echo json_encode(array("code" => "Error", "message" => "Subject does not exists"));
            }
        array_push($exams_arr["exams"], $exam_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show users data in json format
    echo json_encode($exams_arr);
} else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no record found
    echo json_encode(
        array("message" => "No examination found.")
    );
}

?>