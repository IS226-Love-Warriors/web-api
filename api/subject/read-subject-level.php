<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/subject.php';
include_once '../object/user.php';
include_once '../object/criteria.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$subject = new Subject($db);
$user = new User($db);
$criteria = new Criteria($db);

$subject_item = [];
$d = [];

// get posted data
$data = json_decode(file_get_contents("php://input"));
$subject_arr = array();
$subjects_arr["records"] = array();
if(!empty($data)){
    $subject->grade_year = $data->grade_year;

    $stmt_teacher = $subject->readByLevel();
    $num = $stmt_teacher->rowCount();

    if($num > 0){
        while ($subj_row = $stmt_teacher->fetch(PDO::FETCH_ASSOC)){
        
            extract($subj_row);
            $user->user_id = $assigned_teacher;
            $stmtu = $user->readOneById();
            $numu = $stmtu->rowCount();
            
            $subject_item["subject_id"] = $subject_id;
            $subject_item["subject_name"] = $subject_name;
            $subject_item["level"] = $level;
            $subject_item["acad_year"] = $acad_year;
            $subject_item["grade_year"] = $grade_year;
    
            while ($rowu = $stmtu->fetch(PDO::FETCH_ASSOC)){
                extract($rowu);
                $d["user_id"] = $user_id;
                $d["name"] = $first_name . ' ' . $last_name;
                $d["email"] = $email;
                $subject_item["assigned_teacher"] = $d;
                array_push($subjects_arr["records"], $subject_item);   
            }
        }

        http_response_code(200);
        echo json_encode(array("code" => "Ok", "message" => "Record fetched", "data" => $subjects_arr));
    } else {
        http_response_code(200);
        echo json_encode(array("code" => "Conflict", "message" => "No subject associated with this grade level", "data" => $subject_arr));
    }
}
else{
    // set response code - 400 bad request
    http_response_code(403);
    // tell the user
    echo json_encode(array("message" => "Identifier cannot be emptied"));
}
?>