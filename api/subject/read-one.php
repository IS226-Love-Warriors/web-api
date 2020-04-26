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
include_once '../object/criteria.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$subject = new Subject($db);
$user = new User($db);
$criteria = new Criteria($db);

$subject_item = [];
$d = [];

// get posted data
$data = json_decode(file_get_contents("php://input"));
$subject_item["enrolled_students"] = array();
$subject_item["criterias"] = array();
if(!empty($data)){
    $subject->subject_id = $data->subject_id;

    $stmt = $subject->readBySubjId();
    $num = $stmt->rowCount();

    if($num > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $subject_item["id"] = $id;
            $subject_item["subject_id"] = $subject_id;
            $subject_item["subject_name"] = $subject_name;
            $subject_item["level"] = $level;
            $subject_item["acad_year"] = "2019 - 2020";
            $subject_item["grade_year"] = $grade_year;
            $subject_item["assigned_teacher"]["user_id"]= $user_id;
            $subject_item["assigned_teacher"]["name"] = $first_name . " " . $last_name;
        }

        $user->grade_year = $subject_item["grade_year"];
        $stud_stmt = $user->readByYearLevel();
        while ($stud_row = $stud_stmt->fetch(PDO::FETCH_ASSOC)){
            extract($stud_row);
            $student_item = array(
                "user_id" => $user_id,
                "name" => $first_name . " " . $last_name
            );
            array_push($subject_item["enrolled_students"], $student_item);
        }

        $criteria->subject_id = $data->subject_id;
        $criteria_stmt = $criteria->readBySubjectId();
        while ($criteria_row = $criteria_stmt->fetch(PDO::FETCH_ASSOC)){
            extract($criteria_row);
            $criteria_item = array(
                "criteria_id" => $criteria_id,
                "criteria_name" => $criteria_name
            );
            array_push($subject_item["criterias"], $criteria_item);
        }

        
        http_response_code(200);
        echo json_encode(array("code" => "Ok", "message" => "Record fetched", "data" => $subject_item));
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