<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/subject.php';
include_once '../object/user.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$subject = new Subject($db);
$user = new User($db);
$teacher = [];

// get posted data
$data = json_decode(file_get_contents("php://input"));
    // make sure data is not empty
    if(!empty($data)){
    $subject->subject_id = uniqid('subject_');
    for($x = 0; $x < count($data->criteria); $x++){
        $subject->subject_name = $data->subject_name;
        $subject->level = $data->level;
        $subject->grade_year = $data->grade_year;
        $subject->acad_year = $data->acad_year;
        $subject->criteria_name = $data->criteria[$x]->criteria_name;
        $subject->percentage = $data->criteria[$x]->percentage;
        $subject->assigned_teacher = $data->assigned_teacher;
        $subject->create();
    }

    $user->user_id =$data->assigned_teacher;
    $stmtu = $user->readOneById();
    $numu = $stmtu->rowCount();

    while ($rowu = $stmtu->fetch(PDO::FETCH_ASSOC)){
        extract($rowu);
        $teacher["id"] = $id;
        $teacher["user_id"] = $user_id;
        $teacher["name"] = $first_name . ' ' . $last_name;   
    }
        unset($data->assigned_teacher);
        $data->assigned_teacher = $teacher;
        http_response_code(201);
        echo json_encode(array("message" => "Subject was created.", "data" => $data));
}
  
// tell the subject data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the subject
    echo json_encode(array("message" => "Unable to create subject. Data is incomplete."));
}
?>