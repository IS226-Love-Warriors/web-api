<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/user.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$user = new User($db);
$level_arr = array();
$user_stmt = $user->readAllYearLevel();
$num = $user_stmt->rowCount();
if($num>0){
while ($level_row = $user_stmt->fetch(PDO::FETCH_ASSOC)){
    extract($level_row);
    $level_item = array(
        "grade_year_level" => $grade_year_level
    );
    array_push($level_arr, $level_item);
}
http_response_code(200);
echo json_encode(array("code" => "Ok", "message" => "Record fetched","data" => $level_arr));
} else {
    http_response_code(404);
    echo json_encode(array("code" => "Error", "message" => "No Student record yet."));
}
?>