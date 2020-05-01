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
    $grades_arr = array();
    $grades_arr["criteria"] = array();
    $grades->student_id = $data->student_id;
    $grades->subject_id = $data->subject_id;
    $grades_stmt = $grades->getGradesBreakdown();
    $num = $grades_stmt->rowCount();

    while ($grades_row = $grades_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($grades_row);
        $grades_arr["student_id"] = $student_id;
        $grades_arr["name"] = $first_name . " " . $last_name;
        $grades_arr["subject_id"] = $subject_id;
        $grades_arr["subject_name"] = $subject_name;
        
        $grade_item = array(
            "criteria_id" => $criteria_id,
            "criteria_name" => $criteria_name,
            "score" => $score,
            "percentage" => $percentage,
            "score_equivalent" => $score_equivalent
        );
        array_push($grades_arr["criteria"], $grade_item);
    }
    
    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Record fetched","data" => $grades_arr));
}
// tell the user credentials are incomplete
else{
    // set response code - 400 bad request
    http_response_code(403);
    // tell the user
    echo json_encode(array("message" => "Identifier cannot be emptied"));
}
?>