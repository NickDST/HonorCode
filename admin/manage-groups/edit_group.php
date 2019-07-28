<?php 
include "../../templates/admin_header.php";
//include "../../templates/admin_group_edit.php";
$is_production = true;

//migrated from the admin_group_edit.php template file to here because it made more sense
$group_id = mysqli_real_escape_string($connection, $_GET['groupid']);
$sql = "SELECT * FROM `groups_list` WHERE group_id = '$group_id'";
    $result = mysqli_query( $connection, $sql );
    if($result){
            while ( $row = $result->fetch_assoc() ):
                $group_name = $row['group_name'];
                $group_description = $row['group_description'];
                $advisor_contact = $row['advisor_contact'];
                $group_type = $row['group_type'];
                $tutoring_services = $row['tutoring_services'];
                $date_added = $row['date_added'];
            endwhile;
} 

if(mysqli_num_rows( $result ) != 1){
    Redirect("manage_groups?error=Group Does Not Exist");
}


//Does this group provide tutoring services
if($tutoring_services == "yes"){
    $offering_tutoring = "Yes. This group provides tutoring";
} else {
    $offering_tutoring = "No. This group cannot provide tutoring";
}

//for when deleting a group
if(isset($_POST['remove_group'])){
    $sql1 = "DELETE from `groups_list` WHERE group_id = '$group_id'";
    $sql2 = "DELETE from `users_in_groups` WHERE group_id = '$group_id'";

    $result = mysqli_query($connection, $sql2);

    if($result){
        $result2 = mysqli_query($connection, $sql1);
        if($result2){

            Redirect("manage_groups?success=Group Successfully Deleted");
        }
        Redirect("manage_groups?error=Group Unsuccessfully Deleted");
    }
    Redirect("manage_groups?error=Group Membership Unsuccessfully Deleted");

}

if(isset($_POST['update_entries'])){
    //the entries that can be updated
    $update_form_values = array("group_name", "group_description", "advisor_contact", 'group_type', 'tutoring_services');

    //creating an associative array 
    $update_array = create_update_form_array($update_form_values);
    $table = "groups_list";
    $unique_id = "group_id";
    $unique_id_value = $group_id;

    update_if_exists($connection, $update_array, $table, $unique_id, $unique_id_value);

    //Reload the page to view changes
    if($is_production){
        Redirect("edit_group?groupid=$group_id");
    }
}
?>
<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-info" ><?php echo $group_name ?></h3>
                                <hr>
                                <p><strong><?php echo $group_description ?></strong></p>
                                <p>Advisor Contact: <?php echo $advisor_contact ?></p>
                                <p>Group Type: <?php echo $group_type ?></p>
                                <p>Tutoring Services: <?php echo $offering_tutoring ?></p>
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
                                    <input name = "group_name" type="text" class="form-control" id="formGroupExampleInput" placeholder="Example input">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Change Description/Requirements</label>
                                    <input name = "group_description" type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Change Advisor Contact</label>
                                    <input name = "advisor_contact" type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Change Group Type</label>
                                    <br>
                                    <select name="group_type" id="" class="custom-select">
                                            <option value ="" >Please choose one of the following:</option>
                                            <option value="Honor Society">Honor Society</option>
                                            <option value="Club">Normal Group.</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Can this Group Tutor</label>
                                    <br>
                                    <select name="tutoring_services" id="" class="custom-select">
                                            <option value ="" >Please choose one of the following:</option>
                                            <option value="yes">Yes, this group may provide tutoring services</option>
                                            <option value="no">No, this group may not provide tutoring services</option>
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
                              <h3 class="list-group-item list-group-item-action list-group-item-secondary" > Group Leaders </h3>
                                <hr>
                                    <table class="table">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Revoke</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT users.*, users_in_groups.* FROM users, users_in_groups WHERE users_in_groups.user_id = users.user_id 
                                        AND users_in_groups.group_id = '$group_id' AND users_in_groups.is_group_leader = 'yes'";
                                        $result = mysqli_query( $connection, $sql );
                                        if($result){
                                                while ( $row = $result->fetch_assoc() ):
                                                    $username = $row['username'];
                                                    $email = $row['email'];
                                                    $user_id = $row['user_id'];
                                                    echo "
                                                    <tr class='table-dark'>
                                                    <th scope='row'>$user_id</th>
                                                    <td>$username</td>
                                                    <td>$email</td>
                                                    <td><a class = 'btn btn-danger' href = 'revoke_user_leadership?groupid=$group_id&userid=$user_id'> Revoke/Remove </a></td>
                                                    </tr>
                                                    ";
                                                endwhile;
                                    }?>

                                    </tbody>
                                    </table>
                                    <a class = 'btn btn-primary' href = 'add_group_leaders?groupid=<?php echo $group_id ?>'> Add Group Leaders</a>
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
                                <form method="POST">
                                <button type="submit" class="btn btn-danger mb-25" name = "remove_group">Delete/Remove Group from Directory</button>
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
