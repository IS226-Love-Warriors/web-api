<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

// get posted data
$data = json_decode(file_get_contents("php://input"));
    // make sure data is not empty
    if(!empty($data->username) && !empty($data->email) && ! empty($data->firstname) && !empty($data->lastname)){
    // set product property values
    $user->id = $data->id;
    $user->username = $data->username;
    $user->email = $data->email;
    $user->password = md5($data->password);
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    // create the product
    if($user->create()){
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "User was created."));
    }
  
    // if unable to create the product, tell the user
    else{
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create user."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}
?>