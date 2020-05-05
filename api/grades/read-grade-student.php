<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/student_subject_grade.php';
include_once '../object/user.php';
include_once '../object/subject.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$grades = new StudentSubjectGrade($db);
$teacher = new User($db);
$subject = new Subject($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

if(!empty($data)){
    $stmt = $grades->getAllStudent();
    $num = $stmt->rowCount();
    $users_arr = array();
    $users_arr["grades"] = array();
    $grades_arr = array();
    if($num > 0){
        
        $grades->student_id = $data->student_id;
        $grades_stmt = $grades->getAllStudentWithGradesPerSubject();
        while ($grades_row = $grades_stmt->fetch(PDO::FETCH_ASSOC)){
            extract($grades_row);
            $users_arr["student_id"] = $student_id;
            $users_arr["name"] = $first_name . " " . $last_name;

            $teacher->user_id = $assigned_teacher;
            $tchr_stmt = $teacher->readOneById();
            while ($tchr_row = $tchr_stmt->fetch(PDO::FETCH_ASSOC)){
                extract($tchr_row);
                $grades_item = array(
                    "grading_period" => $grading_period,
                    "subject_id" => $subject_id,
                    "subject_name" => $subject_name,
                    "grade" => $grade,
                    "teacher" => $first_name . " " . $last_name
                );
            }            
            array_push($users_arr["grades"], $grades_item);
        }

        if(count($users_arr["grades"])>0){
            http_response_code(200);
            echo json_encode(array("code" => "Ok", "message" => "Record fetched","data" => $users_arr));
        } else{
            $teacher->user_id = $data->student_id;
            $stud_stmt = $teacher->readOneById();
            while ($stud_row = $stud_stmt->fetch(PDO::FETCH_ASSOC)){
                extract($stud_row);
                $users_arr["student_id"] = $data->student_id;
                $users_arr["name"] = $first_name . " " . $last_name;

                $subject->grade_year = $grade_year_level;
                $subject_stmt =  $subject->readByLevel();
                while ($subj_row = $subject_stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($subj_row);
                    $grades_item = array(
                        "grading_period" => 0,
                        "subject_id" => $subject_id,
                        "subject_name" => $subject_name,
                        "grade" => 0,
                        "teacher" => $first_name . " " . $last_name
                    );
                    array_push($users_arr["grades"], $grades_item);
                }
            }


            http_response_code(200);
            echo json_encode(array("code" => "OK", "message" => "No Record fetched","data" => $users_arr));
        }
        


    } else {
        http_response_code(404);
        echo json_encode(array("code" => "Error", "message" => "User does not exists"));
    }
}
// tell the user credentials are incomplete
else{
    // set response code - 400 bad request
    http_response_code(403);
    // tell the user
    echo json_encode(array("message" => "Identifier cannot be emptied"));
}
?>