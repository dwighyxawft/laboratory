<?php
$active = 0;
include("../middleware/auth_header.php");
$user_id = $_SESSION["user_id"];
html($active);
$experiment_id = $_GET["experiment_id"];
$objects->query = "SELECT * FROM experiments WHERE id = '$experiment_id' AND status='pending'";
if($objects->total_rows() > 0){
    $experiment = $objects->query_result();
}else{
    echo "<script>alert('This experiment does not exist')</script>";
    echo $objects->redirect("templates/dashboard.php");
}
?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- Empty column on the left -->
            </div>
            <div class="col-md-6 my-3">
                <h4 class="text-center mb-3">Experiment Settings</h4>
                <form method="post" enctype="multipart/form-data" action="../controllers/experiment_settings.php">
                    <div class="mb-3 form-group">
                        <strong><label for="experimentImage" class="form-label">Experiment Image</label></strong>
                        <input type="file" class="form-control" name="experimentImage">
                    </div>
                    <div class="mb-3 form-group">
                        <strong><label for="experimentName" class="form-label">Experiment Name</label></strong>
                        <input type="text" class="form-control" name="experimentName" value="<?php echo $experiment["name"];?>">
                    </div>
                    <div class="mb-3 form-group">
                        <strong><label for="experimentDuration" class="form-label">Experiment Duration</label></strong>
                        <input type="text" class="form-control" name="experimentDuration" value="<?php echo $experiment["duration"];?>">
                    </div>
                    <div class="mb-3 form-group">
                        <strong><label for="experimentDate" class="form-label">Experiment Date</label></strong>
                        <input type="date" class="form-control" name="experimentDate" value="<?php echo $experiment["date"];?>">
                    </div>
                    <div class="mb-3 form-group">
                        <strong><label for="experimentTime" class="form-label">Experiment Time</label></strong>
                        <input type="time" class="form-control" name="experimentTime" value="<?php echo $experiment["time"];?>">
                    </div>
                    <input type="hidden" name="id" value="<?php echo $experiment["id"];?>">
                    <div class="text-center">
                        <button type="submit" class="btn btn-dark">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-md-3">
                <!-- Empty column on the right -->
            </div>
        </div>
    </div>
</main>

<?php include("../middleware/footer.php");?>