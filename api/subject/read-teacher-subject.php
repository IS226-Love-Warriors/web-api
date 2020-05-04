<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../object/subject.php';
include_once '../object/user.php';

$database = new Database();
$db = $database->getConnection();
  
// initialize object
$subject = new Subject($db);
$user = new User($db);
$stmt = $subject->read();
$num = $stmt->rowCount();

// get posted data
$data = json_decode(file_get_contents("php://input"));
if($num>0){
    $subjects_arr=array();
    $subjects_arr["records"] = array();
    $subject->assigned_teacher = $data->teacher_id;
    $stmt_teacher = $subject->readByAssignedTeacher();
    $numu = $stmt_teacher->rowCount();

    if($numu>0){
        // while ($subj_row = $stmt_teacher->fetch(PDO::FETCH_ASSOC)){
        //     extract($subj_row);
        //     print_r($subj_row);
        //     $subject_item = array(
        //         "subject_id" => $subject_id,
        //         "subject_name" => $subject_name,
        //         "level" => $level,
        //         "acad_year" => $acad_year,
        //         "grade_year" => $grade_year
        //     );

        //     array_push($subjects_arr, $subject_item);
        // }

        while ($subj_row = $stmt_teacher->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($subj_row);
            $user->user_id = $assigned_teacher;
            $stmtu = $user->readOneById();
            $numu = $stmtu->rowCount();
            
            $subject_item["subject_id"] = $subject_id;
            $subject_item["subject_name"] = $subject_name;
            $subject_item["level"] = $level;
            $subject_item["acad_year"] = $acad_year;
            $subject_item["grade_year"] = $grade_year;
    
            while ($rowu = $stmtu->fetch(PDO::FETCH_ASSOC)){
                extract($rowu);
                $d["user_id"] = $user_id;
                $d["name"] = $first_name . ' ' . $last_name;
                $d["email"] = $email;
                $subject_item["assigned_teacher"] = $d;
                array_push($subjects_arr["records"], $subject_item);   
            }
        }

        http_response_code(200);
        echo json_encode(array("code" => "Ok", "message" => "Record fetched", "data" => $subjects_arr));
    } else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No subject assigned yet to this teacher.")
        );
    }
} else{
    http_response_code(404);
    echo json_encode(
        array("message" => "No subjects found.")
    );
}
?>