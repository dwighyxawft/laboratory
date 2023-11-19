<?php
require("../middleware/objects.php");
$objects = new Objects;


$image = $_FILES["apparatusImage"]["name"];
$tmp_name = $_FILES["apparatusImage"]["tmp_name"];
$name = $_POST["name"];
$usage = $_POST["usage"];
echo $name;
echo $usage;
echo $image;
if(move_uploaded_file($tmp_name, "../images/apparatus/$image")){
    $objects->query = "INSERT INTO apparatus (apparatus_name, apparatus_image, apparatus_usage) VALUES ('$name', '$image', '$usage')";
    if($objects->execute_query()){
        echo "<script>alert('Apparatus has been uploaded successfully')</script>";
        echo $objects->redirect("templates/apparatus.php");
    }else{
        echo "<script>alert('Apparatus has not been uploaded successfully')</script>";
        echo $objects->redirect("templates/apparatus.php");
    }
}else{
    echo "<script>alert('Apparatus has not been uploaded successfully')</script>";
    echo $objects->redirect("templates/apparatus.php");
}



?>