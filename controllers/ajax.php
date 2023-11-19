<?php
require("../middleware/objects.php");
$objects = new Objects;
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : false;
if($user_id){
    $objects->query = "SELECT * FROM users WHERE id = '$user_id'";
    $user = $objects->query_result();
}

if(isset($_POST["page"])){

    if($_POST["page"] == "user"){

        if($_POST["action"] == "login"){
            $mail = $_POST["email"];
            $pass = $_POST["password"];
            $objects->query = "SELECT * FROM users WHERE email = '$mail'";
            if($objects->total_rows() > 0){
                $result = $objects->query_result();
                if(password_verify($pass, $result["password"])){
                    $_SESSION["user_id"] = $result["id"];
                    $output = ["status"=>true, "name"=>$result["name"]];
                }else{
                    $output = ["status"=>false, "msg"=>"Password is incorrect"];
                }
            }else{
                $output = ["status"=>false, "msg"=>"User does not exist"];
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "signup"){
            $name = $_POST["name"];
            $email = $_POST["email"];
            $phone = $_POST['phone'];
            $gender = $_POST["gender"];
            $position = $_POST["position"];
            $image = $gender == "male" ? "male.jpg" : "female.jpg";
            $password = $_POST['password'];
            $confirm = $_POST["confirmPassword"];
            $date = date("Y-m-d");
            $objects->query = "SELECT * FROM users WHERE email = '$email'";
            if($objects->total_rows() < 1){
                if($password == $confirm){
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $objects->query = "INSERT INTO users (name, email, phone, gender, password, image, position, created_at) VALUES ('$name', '$email', '$phone', '$gender', '$hash', '$image', '$position', '$date')";
                    if($objects->execute_query()){
                        $output = ['status'=>true];
                    }
                }else{
                    $output = ['status'=>false, "msg"=>'Password is not matching'];
                }
            }else{
                $output = ['status'=>false, 'msg'=>'User already exists'];
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "settings"){
            $name = $_POST["name"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            if($user["email"] == $email){
                $objects->query = "UPDATE users SET name = '$name', email='$email', phone = '$phone' WHERE id = '$user_id'";
                if($objects->execute_query()){
                    $output = ["status"=>true];
                }
            }else{
                $objects->query = "SELECT * FROM users WHERE email = '$email'";
                if($objects->total_rows() > 0){
                    $output = ["status"=>false, "msg"=>"User already exists, Please use another email"];
                }else{
                    $objects->query = "UPDATE users SET name = '$name', email='$email', phone = '$phone' WHERE id = '$user_id'";
                    if($objects->execute_query()){
                        $output = ["status"=>true];
                    }
                }
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "password"){
            $old = $_POST["old_pass"];
            $new = $_POST["new_pass"];
            $con = $_POST["con_pass"];
            if(password_verify($old, $user["password"])){
                if($new == $con){
                    $hash = password_hash($new, PASSWORD_DEFAULT);
                    $objects->query = "UPDATE users SET password = '$hash' WHERE id = '$user_id'";
                    if($objects->execute_query()){
                        $output = ["status"=>true];
                    }
                }else{
                    $output = ["status"=>false, "msg"=>"Passwords are not matching"];
                }
            }else{
                $output = ["status"=>false, "msg"=>"Password is incorrect"];
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "user_settings"){
            $name = $_POST["name"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $id = $_POST["id"];
            $objects->query = "SELECT * FROM users WHERE id = '$id'";
            $user = $objects->query_result();
            if($user["email"] == $email){
                $objects->query = "UPDATE users SET name = '$name', email='$email', phone = '$phone' WHERE id = '$id'";
                if($objects->execute_query()){
                    $output = ["status"=>true];
                }
            }else{
                $objects->query = "SELECT * FROM users WHERE email = '$email'";
                if($objects->total_rows() > 0){
                    $output = ["status"=>false, "msg"=>"User already exists, Please use another email"];
                }else{
                    $objects->query = "UPDATE users SET name = '$name', email='$email', phone = '$phone' WHERE id = '$id'";
                    if($objects->execute_query()){
                        $output = ["status"=>true];
                    }
                }
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "user_password"){
            $old = $_POST["old_pass"];
            $new = $_POST["new_pass"];
            $con = $_POST["con_pass"];
            $id = $_POST["id"];
            $objects->query = "SELECT * FROM users WHERE id = '$id'";
            $user = $objects->query_result();
            if(password_verify($old, $user["password"])){
                if($new == $con){
                    $hash = password_hash($new, PASSWORD_DEFAULT);
                    $objects->query = "UPDATE users SET password = '$hash' WHERE id = '$id'";
                    if($objects->execute_query()){
                        $output = ["status"=>true];
                    }
                }else{
                    $output = ["status"=>false, "msg"=>"Passwords are not matching"];
                }
            }else{
                $output = ["status"=>false, "msg"=>"Password is incorrect"];
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "delete"){
            $id = $_POST["id"];
            $objects->query = "DELETE FROM users WHERE id = '$id'";
            if($objects->execute_query()){
                $output = ["status"=>true];
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "delete-apparatus"){
            $id = $_POST["id"];
            $objects->query = "DELETE FROM apparatus WHERE id = '$id'";
            if($objects->execute_query()){
                $output = ["status"=>true];
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "attendance"){
            $students_length = $_POST["student_length"];
            $exp_id = $_POST["exp_id"];
            for($i = 1; $i<=$students_length; $i++){
                $student_name = $_POST["student_name_".$i];
                $student_matric = $_POST["student_matric_".$i];
                $objects->query = "INSERT INTO attendance (exp_id, student_name, student_matric) VALUES ('$exp_id', '$student_name', '$student_matric')";
                $objects->execute_query();
            }
            echo json_encode(["status"=>true]);
        }

        if($_POST["action"] == "experiment_apparatus_add"){
            $app_id = $_POST["id"];
            $exp_id = $_POST["exp_id"];
            $objects->query = "INSERT INTO apparatus_used (exp_id, app_id) VALUES ('$exp_id', '$app_id')";
            if($objects->execute_query()){
                $output = ["status"=>true];
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "experiment_apparatus_delete"){
            $app_id = $_POST["id"];
            $exp_id = $_POST["exp_id"];
            $objects->query = "DELETE FROM apparatus_used WHERE app_id = '$app_id' AND exp_id = '$exp_id'";
            if($objects->execute_query()){
                $output = ["status"=>true];
            }
            echo json_encode($output);
        }

        if($_POST["action"] == "report"){
            $exp_id = $_POST["exp_id"];
            $supervisor = $_POST["supervisor"];
            $procedure = $_POST["procedure"];
            $results = $_POST["results"];
            $conclusion = $_POST["conclusion"];
            $objects->query = "SELECT * FROM reports WHERE exp_id = '$exp_id'";
            if($objects->total_rows() < 1){
                $objects->query = "INSERT INTO reports (exp_id, supervisor, procedures, results, conclusion) VALUES ('$exp_id', '$supervisor', '$procedure', '$results', '$conclusion')";
                if($objects->execute_query()){
                    $output = ["status"=>true];
                }else{
                    $output = ["status"=>false];
                }
            }else{
                $objects->query = "UPDATE reports SET supervisor = '$supervisor', procedures = '$procedure', results = '$results', conclusion = '$conclusion' WHERE exp_id = '$exp_id'";
                if($objects->execute_query()){
                    $output = ["status"=>true];
                }else{
                    $output = ["status"=>false];
                }
            }
            
            echo json_encode($output);
        }

        if($_POST["action"] == "stop_test"){
            $exp_id = $_POST["exp_id"];
            $objects->query = "UPDATE experiments SET status = 'completed' WHERE id = '$exp_id'";
            if($objects->execute_query()){
                $output = ["status"=>true];
            }
            echo json_encode($output);
        }

    }
    
}

?>