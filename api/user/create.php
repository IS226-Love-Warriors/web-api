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
include_once '../object/student_subject_grade.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$user = new User($db);
$subject = new Subject($db);
$subject_student_grade = new StudentSubjectGrade($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
    // make sure data is not empty
    if(!empty($data)){
    //USER fields : email, password, account_type, user_id, first_name, last_name, grade_year_level, acad_year
    // set user property values

    $user->email = $data->email;
    $user->account_type = $data->account_type;
    $user->password = md5($data->password);

    if($data->account_type == 1){
        $user->user_id = uniqid('admin_');
    }
    if($data->account_type == 2){
        $user->user_id = uniqid('tchr_');
    }
    else{
        $user->user_id = uniqid('stdnt_');
    }
    
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
            if($data->account_type == 3){
                $subject->grade_year = $data->grade_year_level;
                $subj_stmt = $subject->readByLevel();
                $subj_num = $subj_stmt->rowCount();
                if($subj_num <= 0){
                    http_response_code(404);
                    echo json_encode(array("message" => "No available subjects yet."));
                }
                else{
                    while ($row = $subj_stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row);
                        for($x = 1; $x<5; $x++){
                            $final_grade = 0;
                            $subject_student_grade->student_id = $user->user_id;
                            $subject_student_grade->subject_id = $subject_id;
                            $subject_student_grade->grading_period = $x;
                            $subject_student_grade->criteria_name = $criteria_name;
                            $subject_student_grade->score = 0;
                            $subject_student_grade->percentage = $percentage;
                            $subject_student_grade->score_equivalent = 0;
                            $subject_student_grade->ssgCreate();
                        }
                    }
                }
            }
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