<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/student_subject_grade.php';
include_once '../object/user.php';
include_once '../object/subject.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$grade = new StudentSubjectGrade($db);
$student = new User($db);
$subjects = new Subject($db);
$data = json_decode(file_get_contents("php://input"));

$student->user_id = $data->student_id;
$student_stmt = $student->readOneById();
$num = $student_stmt->rowCount();

if($num>0){
    $grades_details = array();
    while($student_row = $student_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($student_row);
        $grades_details["student_id"] = $user_id;
        $grades_details["student_name"] = $first_name . " " . $last_name;
        $grades_details["grade_level"] = $grade_year_level;
        $grades_details["acad_year"] = $acad_year;
        $grades_details["grading_period"] = $data->grading_period;

    }

    $grades_arr = array();
    $grade->student_id = $data->student_id;
    $grade_stmt = $grade->readOne();
    $temp_grade = 0;
    while($grade_row = $grade_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($grade_row);
        if($grading_period == $data->grading_period){
            $subjects->subject_id = $subject_id;
            $subject_stmt = $subjects->readDistinctBySubjId();
            // while($subject_row = $subject_stmt->fetch(PDO::FETCH_ASSOC)){
            //     extract($subject_row);
            //     $grades_item["subject_id"] = $subject_id;
            //     $grades_item["subject_name"] = $subject_name;
            //     $grades_details["subjects"] = array();
            //     $grades_item["grade"] = $temp_grade;
            //     $temp_grade = $temp_grade + $score_equivalent;
            //     array_push($grades_details["subjects"], $grades_item);
            // }
            
        }
    }

    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Records fetched", "data" => $grades_details));
}
else{
    http_response_code(200);
    echo json_encode(
        array("message" => "No records found.")
    );
}
?>