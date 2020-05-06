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
// $subject = new Subject($db);
// $subject_student_grade = new StudentSubjectGrade($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
    if(!empty($data)){

    $user_stmt = $user->readLast();
    $user_count = $user_stmt->rowCount();

    if($user_count > 0){
        while ($user_row = $user_stmt->fetch(PDO::FETCH_ASSOC)){
            extract($user_row);
            if($data->account_type == 1){
                $user->user_id = "2020-1" . ( $id + 5);
            }
            if($data->account_type == 2){
                $user->user_id = "2020-1" . ( $id + 5);
            }
            else{
                $user->user_id = "2020-1" . ( $id + 5);
            }
        }
    } else{
        if($data->account_type == 1){
            $user->user_id = "2020-1";
        }
        if($data->account_type == 2){
            $user->user_id = "2020-1";
        }
        else{
            $user->user_id = "2020-1";
        }
    }
    $user->email = $data->email;
    $user->account_type = $data->account_type;
    $user->password = md5($data->password);
    $user->grade_year_level = $data->grade_year_level;
    $user->acad_year = $data->acad_year;
    $user->first_name = $data->first_name;
    $user->last_name = $data->last_name;
    $user->created_at = date("Y/m/d");
    
    
    $stmt = $user->readOne();
    $num = $stmt->rowCount();

    if($num > 0){
        // set response code - 503 service unavailable
        http_response_code(409);
        // tell the user
        echo json_encode(array("message" => "Email already exists"));
    }
    else{
        // create the user
        if($user->userCreate()){
            unset($user->id);
            unset($user->password);
            http_response_code(201);
            echo json_encode(array("code"=>"Ok", "message" => "User was created.", "data"=> $user));
        }
        else{
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create user."));
        }
    }
    
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}
?>