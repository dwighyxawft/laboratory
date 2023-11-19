<?php
$active = 2;
include("../middleware/auth_header.php");
$user_id = $_SESSION["user_id"];
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
    <div class="container scrollable-container">
        <button class="btn btn-secondary my-3" data-bs-toggle="modal" data-bs-target="#add_apparatus_modal">Add Apparatus</button>
        <div class="row mt-3">
            <?php
                $objects->query = "SELECT * FROM apparatus";
                $get_all = $objects->query_all();
                if(count($get_all) < 1){ ?>
                    <div class="col-md-12 mb-3">
                        <div class="card shadow-sm">
                            <h5 class="text-center py-4">There is no apparatus uploaded to the database</h5>
                        </div>
                    </div>
            <?php
                }else{
                    foreach($get_all as $apparatus){ ?>

                    <div class="col-md-3 mb-3">
                        <div class="card shadow-sm">
                            <img src="../images/apparatus/<?php echo $apparatus["apparatus_image"];?>" alt="Apparatus Image" class="my-1 rounded-circle" height="150">
                            <div class="card-body">
                                <h5 class="card-title text-center"><?php echo $apparatus["apparatus_name"];?></h5>
                                <p class="card-text text-center"><?php echo $apparatus["apparatus_usage"];?></p>
                            </div>
                            <div class="card-footer">
                                <center class="my-1"><button class="btn btn-danger deleteApparatus" data-id="<?php echo $apparatus["id"];?>">Delete</button></center>
                            </div>
                        </div>
                    </div>

                <?php
                    }
                }
                ?>
            
            
        </div>
    </div>
    <div class="modal fade" id="add_apparatus_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Apparatus</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="../controllers/apparatus.php" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <strong><label>Apparatus Image</label></strong>
                            <input type="file" name="apparatusImage" id="" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group mb-3">
                            <strong><label>Apparatus Name</label></strong>
                            <input type="text" name="name" id="" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <strong><label>Apparatus Usage</label></strong>
                            <textarea name="usage" id="" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <center class="mb-3"><button type="submit" class="btn btn-success">Submit</button></center>
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
        var deletes = document.querySelectorAll(".deleteApparatus");
        deletes.forEach(function(delete){
            delete.addEventListener("click", function(){
                var id = delete.getAttribute("data-id");
                $.ajax({
                    url: "../controllers/ajax.php",
                    type: "post",
                    data: {page: "user", action: "delete-apparatus", id: id},
                    dataType: "json",
                    success: function(data){
                        if(data.status){
                            delete.parentNode.parentNode.parentNode.addClass("d-none");
                        }
                    }
                })
            })
        })
    })
</script>
<?php include("../middleware/footer.php");?>