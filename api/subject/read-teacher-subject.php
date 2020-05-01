<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/subject.php';
include_once '../object/user.php';

$database = new Database();
$db = $database->getConnection();
  
// initialize object
$subject = new Subject($db);
$stmt = $subject->read();
$num = $stmt->rowCount();

// get posted data
$data = json_decode(file_get_contents("php://input"));
if($num>0){
    $subjects_arr=array();
    $subject->assigned_teacher = $data->teacher_id;
    $stmt_teacher = $subject->readByAssignedTeacher();
    $numu = $stmt_teacher->rowCount();

    if($numu>0){
        while ($subj_row = $stmt_teacher->fetch(PDO::FETCH_ASSOC)){
            extract($subj_row);
            $subject_item = array(
                "subject_id" => $subject_id,
                "subject_name" => $subject_name
            );

            array_push($subjects_arr, $subject_item);
        }

        http_response_code(200);
        echo json_encode(array("code" => "Ok", "message" => "Record fetched", "data" => $subjects_arr));
    } else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No subject assigned yet to this teacher.")
        );
    }
} else{
    http_response_code(404);
    echo json_encode(
        array("message" => "No subjects found.")
    );
}
?>