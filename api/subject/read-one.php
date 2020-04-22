<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/subject.php';
include_once '../object/user.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$subject = new Subject($db);
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

if(!empty($data)){
    $subject->subject_id = $data->subject_id;

    $stmt = $subject->readBySubjId();
    $num = $stmt->rowCount();

    if($num > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $user->user_id =$assigned_teacher;
            $stmtu = $user->readOneById();
            $numu = $stmtu->rowCount();
            
            $subject_item->id = $id;
            $subject_item->subject_id = $subject_id;
            $subject_item->subject_name = $subject_name;
            $subject_item->level = $level;
            $subject_item->acad_year = $acad_year;
            $subject_item->grade_year = $grade_year;
            
            if($numu > 0){
                while ($rowu = $stmtu->fetch(PDO::FETCH_ASSOC)){
                    
                    extract($rowu);
                    $d->id = $id;
                    $d->user_id = $user_id;
                    $d->name = $first_name . ' ' . $last_name;   
                }

                $subject_item->assigned_teacher = $d;

                  

                http_response_code(200);
                echo json_encode(array("code" => "Ok", "message" => "Records fetched", "data" => $subject_item));
            } 
        }
    } else {
        http_response_code(404);
        echo json_encode(array("code" => "Error", "message" => "Subject does not exists"));
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