<?php include "../../templates/user_header.php";

$project_id = mysqli_real_escape_string( $connection, $_GET[ 'projectid' ] );

$is_production = true;

//migrated from the admin_group_edit.php template file to here because it made more sense
$group_id = mysqli_real_escape_string($connection, $_GET['groupid']);


function update_if_exists($connection, $array, $table, $unique_id, $unique_id_value){
    foreach($array as $x => $value) {
        $escaped_value = mysqli_real_escape_string($connection, $value);
        $sql =  "UPDATE `$table` SET `$x` = '$escaped_value' WHERE $unique_id = '$unique_id_value'"; 
        $result = mysqli_query($connection, $sql);
    }
}


function create_update_form_array($update_form_values){
    $update_array = array();
    for($i = 0; $i < count($update_form_values); $i++){
        if(!empty($_POST[$update_form_values[$i]])){
            $temp_array = array($update_form_values[$i] => $_POST[$update_form_values[$i]]);
            $update_array = array_merge($update_array, $temp_array);
        }
    }
    return $update_array;
}





$sql = "SELECT * FROM `projects` WHERE project_id = '$project_id'";
    $result = mysqli_query( $connection, $sql );
    if($result){
            while ( $row = $result->fetch_assoc() ):
                $project_name = $row['project_name'];
                $requestor_email = $row['requestor_email'];
                $project_details = $row['project_details'];
                $initiated_by = $row['initiated_by'];
                $datetime_started = $row['datetime_started'];
                $type = $row['type'];
            endwhile;
} 

if(mysqli_num_rows( $result ) != 1){
    Redirect("add_service?error=Project Does Not Exist");
}



//for when deleting a group
if(isset($_POST['remove_group'])){
    $sql1 = "DELETE from `projects` WHERE project_id = '$project_id'";
    $sql2 = "DELETE from `users_in_projects` WHERE project_id = '$project_id'";

    $result = mysqli_query($connection, $sql2);

    if($result){
        $result2 = mysqli_query($connection, $sql1);
        if($result2){

            Redirect("add_service?success=Project Successfully Deleted");
        }
        Redirect("add_service?error=Project Unsuccessfully Deleted");
    }
    Redirect("add_service?error=Project Membership Unsuccessfully Deleted");

}

if(isset($_POST['update_entries'])){
    //the entries that can be updated
    $update_form_values = array("project_name", "requestor_email", "project_details", 'datetime_started', 'type', 'tutoring_event');

    //creating an associative array 
    $update_array = create_update_form_array($update_form_values);
    $table = "projects";
    $unique_id = "project_id";
    $unique_id_value = $project_id;

    update_if_exists($connection, $update_array, $table, $unique_id, $unique_id_value);

    //Reload the page to view changes
    if($is_production){
        Redirect("edit_project_details?projectid=$project_id");
    }
}
?>
<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-info" ><?php echo $project_name ?></h3>
                                <hr>
                                <p><strong><?php echo $project_details ?></strong></p>
                                <p>Requestor Contact: <?php echo $requestor_email ?></p>
                                <p>Date Initiated: <?php echo $datetime_started ?></p>
                                <p>Type: <?php echo $type ?></p>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                            <h4>Change/Update Group Information</h4>

                            <form method = "POST">
                                <div class="form-group">
                                    <label for="formGroupExampleInput">Change title</label>
                                    <input name = "project_name" type="text" class="form-control" id="formGroupExampleInput" placeholder="Example input">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Change Description/Requirements</label>
                                    <input name = "project_details" type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Change Requestor Contact</label>
                                    <input name = "requestor_email" type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Date Start</label>
                                    <input name = "datetime_start" type="date" class="form-control" id="formGroupExampleInput2" placeholder="Another input">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Is this a Tutoring Event?</label>
                                    <br>
                                    <select name="tutoring_event" id="" class="custom-select">
                                            <option value ="" >Please choose one of the following:</option>
                                            <option value="yes">Yes, this group may provide tutoring services</option>
                                            <option value="no"> No, this group may not provide tutoring services</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Public or Private Project Event?</label>
                                    <br>
                                    <select name="type" id="" class="custom-select">
                                            <option value ="" >Please choose one of the following:</option>
                                            <option value="private">Private, only the person who initiated the project will be able to see it.</option>
                                            <option value="public"> Public, every person will be able to see the project</option>
                                    </select>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary mb-2" name = "update_entries">Submit</button>
                            </form>

                            </div>
                            <br>
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-secondary" > Project Members </h3>
                                <hr>
                                    <table class="table">
                                    <thead>
                                        <tr>
                                        <th>ID#</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT users.*, users_in_projects.* FROM users, users_in_projects WHERE users_in_projects.user_id = users.user_id 
                                        AND users_in_projects.project_id = '$project_id'";
                                        $result = mysqli_query( $connection, $sql );
                                        if($result){
                                                while ( $row = $result->fetch_assoc() ):
                                                    $username = $row['username'];
                                                    $email = $row['email'];
                                                    $user_id = $row['user_id'];
                                                    $role = $row['role'];
                                                    echo "
                                                    <tr class='table-dark'>
                                                    <th scope='row'>$user_id</th>
                                                    <td>$username</td>
                                                    <td>$email</td>
                                                    <td>$role</td>
                                                    </tr>
                                                    ";
                                                endwhile;
                                    }?>

                                    </tbody>
                                    </table>
                                    <!-- <a class = 'btn btn-primary' href = 'add_group_leaders?groupid=<?php echo $group_id ?>'> Add Group Leaders</a> -->
                                    <br>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div>
<!--  -->
                  <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <br>
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:10px;">
                                    <strong>Only Group Leaders or the Project Creator should delete Projects. Please be responsible.</strong>
                                    <br>
                                    <br>
                                <form method="POST">
                                <button type="submit" class="btn btn-danger mb-25" name = "remove_group">Delete/Remove Project from Directory</button>
                                </form>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div>
<!--  -->
            </div>
        </div>
     </div>
