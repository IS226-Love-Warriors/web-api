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
if($num>0){
    
    $data = json_decode(file_get_contents("php://input"));
    $user->user_id = $data->student_id;
    $student_stmt = $user->readOneById();
    while ($student_row = $student_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($student_row);
        $student_item = array(
            "grade" => $grade_year_level
        );
    }

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
                if($student_item["grade"] == $grade_year){
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
            
    }
    http_response_code(201);
    echo json_encode(array("code"=>"Ok", "message" => "Record fetched", "data"=> $exams_arr));
} else{
    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "No record found.", "data" => $exams_arr));
}

?>