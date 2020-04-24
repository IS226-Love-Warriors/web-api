<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../object/criteria.php';

$database = new Database();
$db = $database->getConnection();
  
$criteria = new Criteria($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data)){
    
    $criteria["criteria_id"] = "criteria_2019_2020_" . " ";
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create criteria. Data is incomplete."));
}

?>