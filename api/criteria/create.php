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
    $criteria_arr = [];
    $criteria_stmt = $criteria->readLast();
    $row_num = $criteria_stmt->rowCount();

    for($x = 0; $x < count($data->criteria); $x++){
        if($row_num == 0){
            $criteria->criteria_id = "criteria_2019_2020_1";
        }
        else{
            while ($criteria_row = $criteria_stmt->fetch(PDO::FETCH_ASSOC)){
                extract($criteria_row); 
                $criteria->criteria_id = "criteria_2019_2020_" . ( $id + 5);
            }
        }
        $criteria->criteria_name = $data->criteria_name;
        $criteria->criteria_item = $data->criteria[$x]->criteria_item;
        $criteria->percentage = $data->criteria[$x]->percentage;
        array_push($criteria_arr, $criteria);
        $criteria->createCriteria();
        
    }
    unset($criteria->id);
    http_response_code(200);
    echo json_encode(array("code"=>"Ok", "message" => "Criteria added", "data" => $criteria_arr));

}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create criteria. Data is incomplete."));
}

?>