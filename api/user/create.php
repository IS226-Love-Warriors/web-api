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
include_once '../object/subject.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$user = new User($db);
$subject = new Subject($db);
$stmt = $subject->read();
$num = $stmt->rowCount();

// get posted data
$data = json_decode(file_get_contents("php://input"));
    // make sure data is not empty
    if(!empty($data)){
        if($data->account_type == '3'){
            // check if more than 0 record found
            if($num>0){
            
                // products array
                $subject_arr=array();
            
                // retrieve our table contents
                // fetch() is faster than fetchAll()
                // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // extract row
                    // this will make $row['name'] to
                    // just $name only
                    extract($row);
            
                    $subject_item=array(
                        $subject_id
                    );
            
                    array_push($subject_arr, $subject_item);
                }
            } else{
            
                // set response code - 404 Not found
                http_response_code(404);
                // tell the user no products found
                echo json_encode(
                    array("message" => "No users found.")
                );
            }

            $user->subjects = $subject_arr;
        }

        //USER fields : email, password, account_type, user_id, first_name, last_name, grade_year_level, acad_year
        // set user property values
        $user->email = $data->email;
        $user->account_type = $data->account_type;
        $user->password = md5($data->password);
        $user->user_id = $data->user_id;
        $user->grade_year_level = $data->grade_year_level;
        $user->acad_year = $data->acad_year;
        $user->first_name = $data->first_name;
        $user->last_name = $data->last_name;


        // create the user
        if($user->create()){
            // set response code - 201 created
            http_response_code(201);
            // tell the user
            echo json_encode(array("message" => "User was created."));
        }
    
        // if unable to create the user, tell the user
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