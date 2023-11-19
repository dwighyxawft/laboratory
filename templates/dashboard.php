<?php
$active = 1;
include("../middleware/auth_header.php");
// $user_id = $_SESSION["user_id"];
html($active);
$date = date("Y-m-d");
$time = date("H:i:s");
$objects->query = "DELETE FROM experiments WHERE (date < '$date' AND status = 'pending')";
$objects->execute_query();
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
            <!-- Sidebar - col-md-3 -->
            <div class="col-md-3">
                <div class="card mt-3">
                    <div class="card-body">
                        <button class="btn btn-primary btn-block w-100 mb-2">Scheduled Experiments</button>
                        <button class="btn btn-success btn-block w-100 mb-2">Current Experiments</button>
                        <button class="btn btn-secondary btn-block w-100">Completed Experiments</button>

                        <!-- User Info Card -->
                        <div class="card mt-3">
                            <div class="card-header text-center">
                                <img src="../images/users/<?php echo $user["image"];?>" class="rounded-circle" width="150" height="150" alt="User Image">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo $user["name"];?></h5>
                                <p class="card-text"><?php echo $user["email"];?></p>
                                <p class="card-text"><strong><?php echo $user["position"];?></strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content - col-md-9 with scrollable container -->
            <div class="col-md-9 scrollable-container"  style="max-height: 80vh; overflow-y: scroll;">
            <a href="add_experiment.php" class="btn btn-secondary btn-sm mt-3 ms-5">Schedule Experiment</a>

                <div class="container mb-4" id="current">
                    <h4 class="ps-3 pt-4">Today's Experiments</h4>
                    <div class="container-fluid">
                        <?php
                            $objects->query = "SELECT * FROM experiments WHERE (date = '$date' AND status = 'pending')";
                            $experiments = $objects->query_all();
                            if(count($experiments) > 0){
                                foreach($experiments as $experiment){
                                    $experiment_time = $experiment["time"];
                                    $experiment_id = $experiment["id"];
                                    $experiment_duration = $experiment["duration"];
                                    $edited_exp_end = new DateTime($experiment_time);
                                    $edited_exp_end->modify("+". $experiment_duration . " minutes");
                                    $experiment_end_time = $edited_exp_end->format("H:i:s");
                                    if($time >= $experiment_time && $time < $experiment_end_time){ ?>
                                        <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div class="d-flex align-items-center">
                                                    <img src="../images/experiments/<?php echo $experiment["image"];?>" alt="experiment" class="img-fluid rounded-circle" width="60"> &nbsp; &nbsp;
                                                    <span class="ml-2"><?php echo $experiment["name"];?></span>
                                                </div>
                                                <div class="d-flex"><h6><?php echo $experiment["duration"];?> minutes</h6></div>
                                                <div class="d-flex">
                                                    <a class="btn btn-primary btn-sm me-2" href="experiment.php?experiment_id=<?php echo $experiment["id"];?>">Start</a>
                                                </div>
                                            </div>
                                        </div>
    
                                   <?php }elseif($time < $experiment_time){ ?>
                                        <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div class="d-flex align-items-center">
                                                    <img src="../images/experiments/<?php echo $experiment["image"];?>" alt="experiment" class="img-fluid rounded-circle" width="60"> &nbsp; &nbsp;
                                                    <span class="ml-2"><?php echo $experiment["name"];?></span>
                                                </div>
                                                <div class="text-center mx-4">
                                                    <b><?php echo $experiment["time"];?></b>
                                                </div>
                                                <div class="d-flex">
                                                    <a href="set_experiment.php?experiment_id=<?php echo $experiment["id"];?>" class="btn btn-success btn-sm me-2">Settings</a>
                                                    <button class="btn btn-danger btn-sm" data-id="<?php echo $experiment["id"];?>">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                   }elseif($time > $experiment_end_time){
                                    $objects->query = "DELETE FROM experiments WHERE id = '$experiment_id'";
                                    $objects->execute_query();
                                   }
                                }
                            }else{ ?>
                                <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                                    <h5 class="text-center">There are no experiments scheduled for today</h5>
                                </div>
                           <?php }
                           
                        ?>
                        <!-- Current Experiment Cards (Repeat as needed) -->
                        
                        
                        
                        <!-- Repeat for other current experiments -->
                    </div>
                </div>

                <div class="container mb-4" id="scheduled">
                    <h4 class="ps-3 pt-4">Scheduled Experiments</h4>
                    <div class="container-fluid">
                        <!-- Scheduled Experiment Cards (Repeat as needed) -->
                        <?php
                            $objects->query = "SELECT * FROM experiments WHERE date > '$date' AND status = 'pending'";
                            if($objects->total_rows() > 0){
                                $experiments = $objects->query_all();
                                foreach($experiments as $experiment){ ?>
                                    <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div class="d-flex align-items-center">
                                                <img src="../images/experiments/<?php echo $experiment["image"];?>" alt="experiment" class="img-fluid rounded-circle" width="60"> &nbsp; &nbsp;
                                                <span class="ml-2"><?php echo $experiment["name"];?></span>
                                            </div>
                                            <div class="text-center mx-4">
                                                <b><?php echo $experiment["date"];?> <?php echo $experiment["time"];?></b>
                                            </div>
                                            <div class="d-flex">
                                                <a href="set_experiment.php?experiment_id=<?php echo $experiment["id"];?>" class="btn btn-success btn-sm me-2">Settings</a>
                                                <button class="btn btn-danger btn-sm" data-id="<?php echo $experiment["id"];?>">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                        <?php   }
                            }else{ ?>
                                <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                                    <h5 class="text-center">There are no scheduled experiments</h5>
                                </div>
                           <?php }
                        ?> 
                        <!-- Repeat for other scheduled experiments -->
                    </div>
                </div>

                <div class="container mb-4" id="completed">
                    <h4 class="ps-3 pt-4">Completed Experiments</h4>
                    <div class="container-fluid">
                        <!-- Completed Experiment Cards (Repeat as needed) -->
                        <?php
                            $objects->query = "SELECT * FROM experiments WHERE status = 'completed'";
                            if($objects->total_rows() > 0){
                                $experiments = $objects->query_all();
                                foreach($experiments as $experiment){ ?>
                                    <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div class="d-flex align-items-center">
                                                <img src="../images/experiments/<?php echo $experiment["image"] ?>" alt="experiment" class="img-fluid rounded-circle" width="60">
                                                <span class="ml-2"><?php echo $experiment["name"] ?></span>
                                            </div>
                                            <div class="text-center mx-4">
                                                <b><?php echo $experiment["date"] ?> <?php echo $experiment["time"] ?></b>
                                            </div>
                                            <div class="d-flex">
                                                <a class="btn btn-primary btn-sm me-2" href="check_details.php?experiment_id=<?php echo $experiment["id"] ?>">Check Details</a>
                                                <a class="btn btn-success btn-sm" href="print_experiment.php?experiment_id=<?php echo $experiment["id"] ?>">Print Details</a>
                                            </div>
                                        </div>
                                    </div>
                        <?php   }
                            }else{ ?>
                                <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                                    <h5 class="text-center">There are no completed experiments</h5>
                                </div>
                           <?php }
                        ?> 
                        

                        <!-- Repeat for other completed experiments -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<?php include("../middleware/footer.php");?>