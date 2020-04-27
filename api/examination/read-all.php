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
$subject = new Subject($db);
$user = new User($db);

$stmt = $exam->read();
$num = $stmt->rowCount();
// check if more than 0 record found
if($num>0){
  
    // user array
    $exams_arr=array();
    $exams_arr["exams"]=array();
    $user_arr = [];
  
    while ($exam_row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($exam_row);
            $subject->subject_id = $subject_id;
            $stmts = $subject->readBySubjId();
            $nums = $stmts->rowCount();

            while ($row = $stmts->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $user->user_id  = $assigned_teacher;
                $user_stmt = $user->readOneById();
                while($user_row = $user_stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($user_row);
                    $user_arr["teacher_id"] = $assigned_teacher;
                    $user_arr["name"] = $first_name . " " . $last_name;
                }
                $exam_item=array(
                    "grading_period" => $grading_period,
                    "exam_id" => $exam_id,
                    "subject" => $subject_name,
                    "exam_date" => $exam_date,
                    "exam_desc" => $exam_desc,
                    "grade" => $grade_year, //filter for student display
                    "academic_year" => $acad_year,
                    "teacher_id" => $user_arr //filter for teacher display
                );

                array_push($exams_arr["exams"], $exam_item);
            }
            
    }
    http_response_code(201);
    echo json_encode(array("code"=>"Ok", "message" => "Record fetched", "data"=> $exams_arr));
} else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no record found
    echo json_encode(
        array("message" => "No examination found.")
    );
}

?>