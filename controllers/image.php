<?php
require("../middleware/objects.php");
$objects = new Objects;
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : false;
if($user_id){
    $objects->query = "SELECT * FROM users WHERE id = '$user_id'";
    $user = $objects->query_result();
}

$image = $_FILES["profileImage"]["name"];
$tmp_name = $_FILES["profileImage"]["tmp_name"];
if(move_uploaded_file($tmp_name, "../images/users/$image")){
    $objects->query = "UPDATE users SET image = '$image' WHERE id = '$user_id'";
    if($objects->execute_query()){
        echo "<script>alert('Profile Image has been updated successfully')</script>";
        echo $objects->redirect("templates/settings.php");
    }
}



?>