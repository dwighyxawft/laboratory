<?php
$active = 0;
include("../middleware/auth_header.php");
// $user_id = $_SESSION["user_id"];
html($active);

?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- Empty column on the left -->
            </div>
            <div class="col-md-6 my-3">
                <h4 class="text-center mb-3">Schedule An Experiment</h4>
                <form class="needs-validation" enctype="multipart/form-data" action="../controllers/experiment.php" method="post">
                    <div class="mb-3 form-group">
                        <strong><label for="experimentImage" class="form-label">Experiment Image</label></strong>
                        <input type="file" class="form-control" id="experimentImage" name="experimentImage">
                    </div>
                    <div class="mb-3 form-group">
                        <strong><label for="experimentName" class="form-label">Experiment Name</label></strong>
                        <input type="text" class="form-control" id="experimentName" name="experimentName">
                    </div>
                    <div class="mb-3 form-group">
                        <strong><label for="experimentDuration" class="form-label">Experiment Duration</label></strong>
                        <input type="number" class="form-control" id="experimentDuration" name="experimentDuration">
                    </div>
                    <div class="mb-3 form-group">
                        <strong><label for="experimentDate" class="form-label">Experiment Date</label></strong>
                        <input type="date" class="form-control" id="experimentDate" name="experimentDate">
                    </div>
                    <div class="mb-3 form-group">
                        <strong><label for="experimentTime" class="form-label">Experiment Time</label></strong>
                        <input type="time" class="form-control" name="experimentTime">
                    </div>
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