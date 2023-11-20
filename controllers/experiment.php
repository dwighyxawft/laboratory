<?php
require("../middleware/objects.php");
$objects = new Objects;

$today = date("Y-m-d");
$current_time = date("H:i:s");
$image = $_FILES["experimentImage"]["name"];
$tmp_name = $_FILES["experimentImage"]["tmp_name"];
$name = $_POST["experimentName"];
$duration = $_POST["experimentDuration"];
$date = $_POST["experimentDate"];
$time = $_POST["experimentTime"];
$created_at = date("Y-m-d");
$adder = $_SESSION["user_id"];
if($date == $today && $time >= $current_time){
    if(move_uploaded_file($tmp_name, "../images/experiments/$image")){
        $objects->query = "INSERT INTO experiments (name, image, duration, date, time, adder_id, created_at) VALUES ('$name', '$image', '$duration', '$date', '$time', '$adder', '$created_at')";
        if($objects->execute_query()){
            echo "<script>alert('Experiment has be scheduled successfully')</script>";
            echo $objects->redirect("templates/dashboard.php");
        }
    
    }
}elseif($date > $today){
    if(move_uploaded_file($tmp_name, "../images/experiments/$image")){
        $objects->query = "INSERT INTO experiments (name, image, duration, date, time, adder_id, created_at) VALUES ('$name', '$image', '$duration', '$date', '$time', '$adder', '$created_at')";
        if($objects->execute_query()){
            echo "<script>alert('Experiment has be scheduled successfully')</script>";
            echo $objects->redirect("templates/dashboard.php");
        }
    
    }
}else{
    echo "<script>alert('Experiment date and time must be above the current time')</script>";
    echo $objects->redirect("templates/dashboard.php");
}




?>