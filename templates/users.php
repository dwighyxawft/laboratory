<?php
$active = 2;
include("../middleware/auth_header.php");
// $user_id = $_SESSION["user_id"];
html($active);

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

        .scrollable-container{
            height: 80vh;
            overflow-y:scroll;
        }
</style>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <!-- Empty column on the left -->
            </div>
            <div class="col-md-8 my-3 scrollable-container">
                <button class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#user_modal">Add User</button>
                
                <?php
                $objects->query = "SELECT * FROM users WHERE id != '$user_id'";
                $users = $objects->query_all();
                foreach($users as $person){ ?>
                    <div class="card mb-3 shadow-sm p-3 d-flex align-items-center">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="d-flex align-items-center">
                                <img src="../images/users/<?php echo $person["image"];?>" alt="user" class="img-fluid rounded-circle" width="60">
                                <span class="ml-2"><?php echo $person["name"];?></span>
                            </div>
                            <div class="d-flex"><h6><?php echo $person["position"];?></h6></div>
                            <div class="d-flex">
                                <a class="btn btn-primary btn-sm me-2" href="user_settings.php?user_id=<?php echo $person["id"];?>">Edit</a>
                                <button class="btn btn-danger btn-sm deleteUser" data-id="<?php echo $person["id"];?>">Delete</button>
                            </div>
                        </div>
                    </div>  

                <?php
                    }
                ?>
                
        
            </div>
            <div class="col-md-2">
                <!-- Empty column on the right -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="user_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" id="signup">
                        <div class="mb-3 form-group">
                            <strong><label class="form-label">Name:</label></strong>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3 form-group">
                            <strong><label class="form-label">Email:</label></strong>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3 form-group">
                            <strong><label class="form-label">Phone Number:</label></strong>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3 form-group">
                            <strong><label class="form-label">Laboratory Position:</label></strong>
                            <input type="text" class="form-control" id="position" name="position">
                        </div>
                        <div class="mb-3 form-group">
                            <strong><label class="form-label">Gender:</label></strong>
                            <select name="gender" id="" class="form-control form-select">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <strong><label class="form-label">Password:</label></strong>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3 form-group">
                            <strong><label class="form-label">Confirm Password:</label></strong>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                        </div>
                        <input type="hidden" name="page" value="user">
                        <input type="hidden" name="action" value="signup">
                        <div class="text-center">
                            <button type="submit" class="btn btn-dark">Submit</button>
                        </div>
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
    $(document).ready(function(){
        $("#signup").on("submit", function(e){
            e.preventDefault();
            $.ajax({
                url: "../controllers/ajax.php",
                type: "post",
                dataType: "json",
                data: $(this).serialize(),
                success: function(data){
                    if(data.status){
                        alert("User has been added successfully");
                        location.reload(true);
                    }else{
                        alert(data.msg);
                    }
                }
            })
        })
        var deletes = document.querySelectorAll(".deleteUser");
        deletes.forEach(function(delete){
            var id = delete.getAttribute("data-id");
            $.ajax({
                url: "../controllers/ajax.php",
                type: "post",
                data: {page: "user", action: "delete", id: id},
                dataType: "json",
                success: function(data){
                    if(data.status){
                        location.reload(true);
                    }
                }
            })
        })
    })
</script>

<?php include("../middleware/footer.php");?>