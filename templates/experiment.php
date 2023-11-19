<?php
$active = 0;
include("../middleware/auth_header.php");
// $user_id = $_SESSION["user_id"];
html($active);
$experiment_id = $_GET["experiment_id"];
$objects->query = "SELECT * FROM experiments WHERE id = '$experiment_id' AND status = 'pending'";
if($objects->total_rows() > 0){
    $experiment = $objects->query_result();
    $experiment_start_time = $experiment["time"];
    $exp_time_settings = new DateTime($experiment_start_time);
    $exp_time_settings->modify("+".$experiment["duration"]." minutes");
    $experiment_end_time = strtotime($exp_time_settings->format("H:i:s"));
    $current_time = strtotime(date("H:i:s"));
    $minutes = floor((($experiment_end_time - $current_time)) / 60);
    $seconds_left = ((($experiment_end_time - $current_time)) % 60);
    $hours = floor($minutes / 60);
    $minutes_left = $minutes % 60;
}else{
    echo "<script>alert('This experiment does not exist')</script>";
    echo $objects->redirect("templates/dashboard.php");
}
$objects->query = "SELECT * FROM reports WHERE exp_id = '$experiment_id'";
$report = $objects->query_result();
?>
<main>
<style>
        .scrollable-container{
            height: 80vh;
        }
        .row{
            height: 80vh;
        }
        main{
            min-height: 80vh;
        }
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
    <div class="container">
        <!-- Experiment Info -->
        <div class="container">
            <div class="card shadow-sm mb-3 p-3 d-flex align-items-center mt-2">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center">
                        <img src="../images/experiments/<?php echo $experiment["image"] ?>" alt="experiment" class="img-fluid rounded-circle" width="60">
                        <h6 class="me-2"><?php echo $experiment["name"] ?></h6>
                    </div>
                    <div class="d-flex"><h4><?php echo $experiment["time"] ?></h4></div>
                    <div class="d-flex"><h4 id="countdown"></h4></div>
                    <div class="d-flex">
                        <button class="btn btn-danger btn-sm me-2" id="stop_test">Stop</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Form -->
        <div class="container-fluid mt-4">
            
            <div class="row">
                <div class="col-md-6 scrollable-container" style="overflow-y: scroll;">
                    <strong class="pt-3"><label for="">Number of Students</label></strong>
                    <select name="" id="student_length" class="form-control form-select mb-4">
                        <?php for($i = 1; $i<=100; $i++){?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php }?>
                    </select>
                    <h3>Student Details</h3>
                    <!-- Repeat this section for each student -->
                    <form id="attendance" method="post">
                        <div class="form-group mb-3">
                            <label for="studentName" class="form-label">Student Details</label>
                            <div class="input-group">
                                <input type="text" class="form-control w-50 me-4" id="studentName" name="student_name_1">
                                <input type="text" class="form-control w-25" id="studentMatric" name="student_matric_1">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="studentName" class="form-label">Student Details</label>
                            <div class="input-group">
                                <input type="text" class="form-control w-50 me-4" id="studentName" name="student_name_2">
                                <input type="text" class="form-control w-25" id="studentMatric" name="student_matric_2">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="studentName" class="form-label">Student Details</label>
                            <div class="input-group">
                                <input type="text" class="form-control w-50 me-4" id="studentName" name="student_name_3">
                                <input type="text" class="form-control w-25" id="studentMatric" name="student_matric_3">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="studentName" class="form-label">Student Details</label>
                            <div class="input-group">
                                <input type="text" class="form-control w-50 me-4" id="studentName" name="student_name_4">
                                <input type="text" class="form-control w-25" id="studentMatric" name="student_matric_4">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="studentName" class="form-label">Student Details</label>
                            <div class="input-group">
                                <input type="text" class="form-control w-50 me-4" id="studentName" name="student_name_5">
                                <input type="text" class="form-control w-25" id="studentMatric" name="student_matric_5">
                            </div>
                        </div>
                        <input type="hidden" name="student_length" value="5">
                        <input type="hidden" name="exp_id" value="<?php echo $experiment_id; ?>">
                        <input type="hidden" name="page" value="user">
                        <input type="hidden" name="action" value="attendance">
                        <center class="mb-3"><button class="btn btn-primary" type="submit">Submit</button></center>
                    </form>
                    <!-- Repeat the form section for each student -->
                </div>

                <!-- Add Apparatus -->
                <div class="col-md-6 scrollable-container pb-4" style="overflow-y: scroll;">
                    <button class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#reportModal">Write Report</button>
                    <h3>Add Apparatus</h3>
                    <div class="row">
                        <!-- Repeat this card for each apparatus -->
                        <?php
                            $objects->query = "SELECT * FROM apparatus";
                            if($objects->total_rows() > 0){
                                $apps = $objects->query_all();
                                foreach($apps as $app){
                                    $app_id = $app["id"];
                                    $objects->query = "SELECT * FROM apparatus_used WHERE exp_id = '$experiment_id' AND app_id = '$app_id'";
                                    if($objects->total_rows() < 1){
                                    ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card shadow-sm">
                                                <img src="../images/apparatus/<?php echo $app["apparatus_image"] ?>" alt="Apparatus Image" class="rounded-circle" height="150">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center"><?php echo $app["apparatus_name"] ?></h5>
                                                    <p class="card-text text-center"><?php echo $app["apparatus_usage"] ?></p>
                                                    <center><button class="btn btn-success addApparatus" data-id="<?php echo $app["id"] ?>">Add</button></center>
                                                </div>
                                            </div>
                                        </div>
                                <?php }else{ ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card shadow-sm">
                                                <img src="../images/apparatus/<?php echo $app["apparatus_image"] ?>" alt="Apparatus Image" class="rounded-circle" height="150">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center"><?php echo $app["apparatus_name"] ?></h5>
                                                    <p class="card-text text-center"><?php echo $app["apparatus_usage"] ?></p>
                                                    <center><button class="btn btn-danger deleteApparatus" data-id="<?php echo $app["id"] ?>">Delete</button></center>
                                                </div>
                                            </div>
                                        </div>  
                                <?php }
                                 }
                            }
                        ?>
                        
                        <!-- Repeat for other apparatus -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Experiment Report</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="report" method="post">
                        <div class="form-group mb-3">
                            <strong><label>Supervisor Name:</label></strong>
                            <input type="text" name="supervisor" id="" value="<?php echo $report["supervisor"];?>" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <strong><label>Procedure:</label></strong>
                            <textarea name="procedure" id="" cols="30" rows="6" class="form-control" max-length="1000"><?php echo $report["procedures"];?></textarea>
                        </div>
                         <div class="form-group mb-3">
                            <strong><label>Results:</label></strong>
                            <textarea name="results" id="" cols="30" rows="5" class="form-control"  max-length="1000"><?php echo $report["results"];?></textarea>
                        </div>
                         <div class="form-group mb-3">
                            <strong><label>Conclusion:</label></strong>
                            <textarea name="conclusion" id="" cols="30" rows="4" class="form-control"  max-length="1000"><?php echo $report["conclusion"];?></textarea>
                        </div>
                        <input type="hidden" name="page" value="user">
                        <input type="hidden" name="action" value="report">
                        <input type="hidden" name="exp_id" value="<?php echo $experiment_id; ?>">
                        <center class="mb-3"><button type="submit" class="btn btn-dark">Submit</button></center>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    // Initial values from PHP
    var hours = Number("<?php echo $hours; ?>");
    var minutes = Number("<?php echo $minutes_left; ?>");
    var seconds = Number("<?php echo $seconds_left; ?>");

    console.log(hours, minutes, seconds);

    // Function to update and display the countdown
    function updateCountdown() {
        var countdownElement = document.querySelector('#countdown');

        // Update the countdown values
        seconds--;

        if (seconds < 0) {
            seconds = 59;
            minutes--;

            if (minutes < 0) {
                minutes = 59;
                hours--;

                if (hours < 0) {
                    // Countdown has reached zero, you can handle this case as needed
                    clearInterval(intervalId);
                    countdownElement.innerHTML = "Countdown Expired";
                    return;
                }
            }
        }

        // Display the updated countdown
        countdownElement.innerHTML = formatTime(hours) + ":" + formatTime(minutes) + ":" + formatTime(seconds);
    }

    // Function to add leading zero if the time component is less than 10
    function formatTime(time) {
        return time < 10 ? "0" + time : time;
    }

    // Initial display of countdown
    updateCountdown();

    // Update the countdown every second
    var intervalId = setInterval(updateCountdown, 1000);
    var students_length = document.querySelector("#student_length");
    students_length.addEventListener("change", function(){
        var studentslength_input = Number(students_length.value);
        var html = "";
        for(var i = 1; i<=studentslength_input; i++){
            html += `<div class="form-group mb-3">
                            <label for="studentName" class="form-label">Student Details</label>
                            <div class="input-group">
                                <input type="text" class="form-control w-50 me-4" id="studentName" name="student_name_${i}">
                                <input type="text" class="form-control w-25" id="studentMatric" name="student_matric_${i}">
                            </div>
                        </div>`;
        }
        html += `<input type="hidden" name="student_length" value="${studentslength_input}">
                 <input type="hidden" name="exp_id" value="${<?php echo $experiment_id; ?>}">
                 <input type="hidden" name="page" value="user">
                 <input type="hidden" name="action" value="attendance">
                 <center class="mb-3"><button class="btn btn-primary" type="submit">Submit</button></center>`;
        document.querySelector("#attendance").innerHTML = html;
    })
    
    $("#attendance").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            url: "../controllers/ajax.php",
            type: "post",
            data: $(this).serialize(),
            dataType: "json",
            success: function(data){
                if(data.status){
                    alert("Students details have been uploaded successfully");
                    location.reload(true);
                }
            }
        })
    })

    var add_apps = document.querySelectorAll(".addApparatus");
    add_apps.forEach(function(add_app){
        add_app.addEventListener("click", function(){
            var id = add_app.getAttribute("data-id");
            console.log(id);
            $.ajax({
                url: "../controllers/ajax.php",
                type: "post",
                data: {page: "user", action: "experiment_apparatus_add", id: id, exp_id: "<?php echo $experiment_id; ?>"},
                dataType: "json",
                success: function(data){
                    if(data.status){
                        add_app.classList.remove("btn-success");
                        add_app.classList.add("btn-danger");
                        add_app.innerText = "Delete";
                    }
                }
            })
        })
    })

    var delete_apps = document.querySelectorAll(".deleteApparatus");
    delete_apps.forEach(function(delete_app){
        delete_app.addEventListener("click", function(){
            var id = delete_app.getAttribute("data-id");
            $.ajax({
                url: "../controllers/ajax.php",
                type: "post",
                data: {page: "user", action: "experiment_apparatus_delete", id: id, exp_id: "<?php echo $experiment_id; ?>"},
                dataType: "json",
                success: function(data){
                    if(data.status){
                        delete_app.classList.remove("btn-danger");
                        delete_app.classList.add("btn-success");
                        add_app.innerText = "Add";
                    }
                }
            })
        })
    })

    $("#report").on("submit", function(e){
        e.preventDefault();
        console.log("submitted");
        $.ajax({
            url: "../controllers/ajax.php",
            type: "post",
            data: $(this).serialize(),
            dataType: "json",
            success: function(data){
                if(data.status){
                    alert("Your report has been uploaded successfully");
                }else{
                    alert("Error uploading report");
                }
            }
        })
    })


    $("#stop_test").on("click", function(e){
        $.ajax({
            url: "../controllers/ajax.php",
            type: "post",
            data: {page: 'user', action: "stop_test", exp_id: "<?php echo $experiment_id; ?>"},
            dataType: "json",
            success: function(data){
                if(data.status){
                    location.href = "check_details.php?experiment_id=<?php echo $experiment_id; ?>"
                }
            }
        })
    })

    

</script>
<?php include("../middleware/footer.php");?>