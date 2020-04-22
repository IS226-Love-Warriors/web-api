<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/answer.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$subject = new Subject($db);
$user = new User($db);

$stmt = $subject->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
  
    // products array
    $subjects_arr=array();
    $subjects_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
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
                $subject_item->assigned_teacher = $d;
                array_push($subjects_arr["records"], $subject_item);
               
            }
        } else {
            http_response_code(200);
            echo json_encode(array("code" => "Ok", "message" => "Records fetched", "data" => $subjects_arr));
        }
    }

} else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No users found.")
    );
}

?>