<?php
$active = 1;
include("../middleware/auth_header.php");
// $user_id = $_SESSION["user_id"];
html($active);
$experiment_id = $_GET["experiment_id"];
$objects->query = "SELECT * FROM experiments WHERE id = '$experiment_id' AND status = 'completed'";
if($objects->total_rows() > 0){
    $experiment = $objects->query_result();
}else{
    echo "<script>alert('This experiment does not exist')</script>";
    echo $objects->redirect("templates/dashboard.php");
}
?>
 <style>
        /* Custom CSS to hide the scrollbar but keep it functional */
        .scrollable-container::-webkit-scrollbar {
            width: 0.5em;
        }

        .scrollable-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollable-container::-webkit-scrollbar-thumb {
            background: transparent;
        }
</style>
<main>
    <div class="container">
        <div class="row">
            <!-- Sidebar - col-md-4 -->
            <div class="col-md-3">
                <div class="card mt-3">
                    <div class="card-body">
                        <button class="btn btn-primary btn-block w-100 mb-2">Apparatus Used</button>
                        <button class="btn btn-success btn-block w-100 mb-2">Students Attendance</button>
                        <button class="btn btn-secondary btn-block w-100">Experiment Report</button>

                        <!-- User Info Card -->
                        <div class="card mt-3">
                            <div class="card-header text-center">
                                <img src="../images/users/<?php echo $user["image"]; ?>" class="rounded-circle" width="150" height="150" alt="User Image">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo $user["name"]; ?></h5>
                                <p class="card-text"><?php echo $user["email"]; ?></p>
                                <p class="card-text"><strong><?php echo $user["position"]; ?></strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content - col-md-9 with scrollable container -->
            <div class="col-md-9 scrollable-container"  style="max-height: 80vh; overflow-y: scroll;">
            <a href="print_experiment.php?experiment_id=<?php echo $experiment_id;?>" class="btn btn-secondary btn-sm mt-3 ms-5">Print Report</a>
                <div class="container mb-4" id="apparatus">
                    <h4 class="ps-3 pt-4">Apparatus Used</h4>
                    <div class="container-fluid">
                        <div class="row">
                            <?php
                                $objects->query = "SELECT * FROM apparatus_used WHERE exp_id = '$experiment_id'";
                                $apps = $objects->query_all();
                                foreach($apps as $app){
                                    $app_id = $app['app_id'];
                                    $objects->query = "SELECT * FROM apparatus WHERE id = '$app_id'";
                                    $apparatus = $objects->query_result(); ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-sm">
                                            <img src="../images/apparatus/<?php echo $apparatus["apparatus_image"];?>" alt="Apparatus Image" class="my-1 rounded-circle" height="150">
                                            <div class="card-body">
                                                <h5 class="card-title text-center"><?php echo $apparatus["apparatus_name"];?></h5>
                                                <p class="card-text text-center">Usage: <?php echo $apparatus["apparatus_usage"];?></p>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="container mb-4" id="students">
                    <h4 class="ps-3 pt-4">Students Attendance</h4>
                    <div class="container-fluid">
                        <!-- Scheduled Experiment Cards (Repeat as needed) -->
                        <?php
                            $objects->query = "SELECT * FROM attendance WHERE exp_id = '$experiment_id'";
                            $students = $objects->query_all();
                            foreach($students as $student){ ?>
                                 <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center">
                                            <h6><?php echo $student["student_name"];?></h6>
                                        </div>
                                        <div class="d-flex">
                                            <strong><?php echo $student["student_matric"];?></strong>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                        
                        <!-- Repeat for other scheduled experiments -->
                    </div>
                </div>

                <div class="container mb-4" id="reports">
                    <h4 class="ps-3 pt-4">Experiment Report</h4>
                    <?php
                        $objects->query = "SELECT * FROM reports WHERE exp_id = '$experiment_id'";
                        $report = $objects->query_result();
                    ?>
                    <div class="container-fluid">
                        <!-- Completed Experiment Cards (Repeat as needed) -->
                        <label><strong>Procedure</strong></label>
                        <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <p><?php echo $report["procedures"];?>
                                </p>
                            </div>
                        </div>
                        <label><strong>Result</strong></label>
                        <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <p>
                                    <?php echo $report["results"];?>
                                </p>
                            </div>
                        </div>
                        <label><strong>Conclusion</strong></label>
                        <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                            <p>
                                <?php echo $report["conclusion"];?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<?php include("../middleware/footer.php");?>