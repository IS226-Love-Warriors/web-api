<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/user.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data)){
    $user->user_id = $data->user_id;
    $user_stmt = $user->readOneById();

    while ($user_row = $user_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($user_row);
        $user->first_name = (!empty($data->first_name)) ? $data->first_name : $first_name;
        $user->last_name = (!empty($data->last_name)) ? $data->last_name : $last_name;
        $user->email = (!empty($data->email)) ? $data->email : $email;
        $user->password = (!empty($data->password)) ? md5($data->password) : $password;
        $user->is_active = (!empty($data->is_active)) ? 0 : $is_active;
        $user->userUpdate();
    }

    http_response_code(200);
    echo json_encode(array("code" => "Ok", "message" => "Record updated"));
}
else{
    // set response code - 400 bad request
    http_response_code(403);
    // tell the user
    echo json_encode(array("message" => "Identifier cannot be emptied"));
}
?>