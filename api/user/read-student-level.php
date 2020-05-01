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

// get posted data
$data = json_decode(file_get_contents("php://input"));

if(!empty($data)){
    $user->grade_year = $data->year_level;

    $stmt = $user->readByYearLevel();
    $num = $stmt->rowCount();
    $users_arr = array();
    if($num > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $d["user_id"] = $user_id;
            $d["first_name"] = $first_name;
            $d["last_name"] = $last_name; 
            array_push($users_arr, $d);
        }

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