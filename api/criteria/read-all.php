<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/criteria.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
$criteria = new Criteria($db);

$criteria_stmt = $criteria->readAll();
$num_row = $criteria_stmt->rowCount();

if($num_row>0){

    $criteria_arr=array();
    $criteria_item = [];

    while ($criteria_row = $criteria_stmt->fetch(PDO::FETCH_ASSOC)){
        extract($criteria_row);
        $criteria_item["criteria_id"] = $criteria_id;
        $criteria_item["criteria_name"] = $criteria_name;
        array_push($criteria_arr, $criteria_item);
    }

    http_response_code(200);
    echo json_encode(array("code"=>"Ok", "message" => "Criteria added", "data" => $criteria_arr));
} else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no record found
    echo json_encode(
        array("message" => "No users found.")
    );
}

?>