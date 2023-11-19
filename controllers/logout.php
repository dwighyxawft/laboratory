<?php
require("../middleware/objects.php");
$objects = new Objects;

session_destroy();
$objects->redirect("templates/login.php");
?>