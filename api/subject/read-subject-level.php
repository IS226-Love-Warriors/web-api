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
if(!empty($data)){
    $subject->grade_year = $data->grade_year;

    $stmt = $subject->readByLevel();
    $num = $stmt->rowCount();

    if($num > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $subject_item["subject_id"] = $subject_id;
            $subject_item["subject_name"] = $subject_name;
            array_push($subject_arr, $subject_item);
        }

        http_response_code(200);
        echo json_encode(array("code" => "Ok", "message" => "Record fetched", "data" => $subject_arr));
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