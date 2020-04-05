<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/user.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$user = new User($db);

$stmt = $user->read();
$num = $stmt->rowCount();
// check if more than 0 record found
if($num>0){
  
    // user array
    $users_arr=array();
    $users_arr["users"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        if($account_type == 3){
            $user_item=array(
                "id" => $id,
                "email" => $email,
                "account_type" => $account_type,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "grade_year_level" => $grade_year_level,
                "acad_year"=>$acad_year
            );
        } else{
            $user_item=array(
                "id" => $id,
                "email" => $email,
                "account_type" => $account_type,
                "first_name" => $first_name,
                "last_name" => $last_name
            );
        }
        
  
        array_push($users_arr["users"], $user_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show users data in json format
    echo json_encode($users_arr);
} else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no record found
    echo json_encode(
        array("message" => "No users found.")
    );
}

?>