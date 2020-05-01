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

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$grades = new StudentSubjectGrade($db);

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

            $grades_item = array(
                "grading_period" => $grading_period,
                "subject_id" => $subject_id,
                "subject_name" => $subject_name,
                "grade" => $grade 
            );
            array_push($grades_arr, $grades_item);
        }
        array_push($users_arr["grades"], $grades_arr);

        http_response_code(200);
        echo json_encode(array("code" => "Ok", "message" => "Record fetched","data" => $users_arr));
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