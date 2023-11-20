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
$id = $_POST["id"];
$created_at = date("Y-m-d");
$adder = $_SESSION["user_id"];
if($date == $today && $time >= $current_time){
    if(move_uploaded_file($tmp_name, "../images/experiments/$image")){
        $objects->query = "UPDATE experiments SET name = '$name', image = '$image', duration = '$duration', date = '$date', time = '$time' WHERE id = '$id'";
        if($objects->execute_query()){
            echo "<script>alert('Experiment has been updated successfully')</script>";
            echo $objects->redirect("templates/set_experiment.php?experiment_id=".$id);
        }else{
            echo "<script>alert('Experiment has not been updated successfully')</script>";
            echo $objects->redirect("templates/set_experiment.php?experiment_id=".$id);
        }
    
    }
}elseif($date > $today){
    if(move_uploaded_file($tmp_name, "../images/experiments/$image")){
        $objects->query = "UPDATE experiments SET name = '$name', image = '$image', duration = '$duration', date = '$date', time = '$time' WHERE id = '$id'";
        if($objects->execute_query()){
            echo "<script>alert('Experiment has been updated successfully')</script>";
            echo $objects->redirect("templates/set_experiment.php?experiment_id=".$id);
        }else{
            echo "<script>alert('Experiment has not been updated successfully')</script>";
            echo $objects->redirect("templates/set_experiment.php?experiment_id=".$id);
        }
    
    }
}else{
    echo "<script>alert('Experiment date and time must be above the current time')</script>";
    echo $objects->redirect("templates/dashboard.php");
}




?>