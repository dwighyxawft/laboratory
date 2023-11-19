<?php
require("../middleware/objects.php");
$objects = new Objects;


$image = $_FILES["experimentImage"]["name"];
$tmp_name = $_FILES["experimentImage"]["tmp_name"];
$name = $_POST["experimentName"];
$duration = $_POST["experimentDuration"];
$date = $_POST["experimentDate"];
$time = $_POST["experimentTime"];
$created_at = date("Y-m-d");
$adder = $_SESSION["user_id"];
if(move_uploaded_file($tmp_name, "../images/experiments/$image")){
    $objects->query = "INSERT INTO experiments (name, image, duration, date, time, adder_id, created_at) VALUES ('$name', '$image', '$duration', '$date', '$time', '$adder', '$created_at')";
    if($objects->execute_query()){
        echo "<script>alert('Experiment has be scheduled successfully')</script>";
        echo $objects->redirect("templates/dashboard.php");
    }

}



?>