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
    $user->email = $data->email;

    $stmt = $user->readOne();
    $num = $stmt->rowCount();

    if($num > 0){
        $enteredPass = md5($data->password);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            if($enteredPass == $password){
                http_response_code(200);
                echo json_encode(array("code" => "Ok", "message" => "Successfully logged in", "data" => "{ id : $id, email : $email, account_type : $account_type, first_name : $first_name, last_name : $last_name }"));
            }
            else {
                http_response_code(401);
                echo json_encode(array("code" => "Error", "message" => "Invalid password"));
            }
        }
    }
}
// tell the user credentials are incomplete
else{
    // set response code - 400 bad request
    http_response_code(403);
    // tell the user
    echo json_encode(array("message" => "Invalid username or password"));
}
?>