<?php 
include "../../templates/user_header.php";

$is_production = true;

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

if(isset($_POST['apply_to_group'])) {
    $additional_details = mysqli_real_escape_string($connection, $_POST['additional_details']);

    // Query check to make sure the user is not already a member
    $sql_check2 = "SELECT * FROM `users_in_groups` WHERE group_id = '$group_id' AND user_id = '$user_id'";
    $result_check2 = mysqli_query($connection, $sql_check);

    if(!$result_check2){

        // Add a query check to make sure the user has not already submitted a request
        $sql_check = "SELECT * FROM `group_requests` WHERE group_id = '$group_id' AND user_id = '$user_id'";
        $result_check = mysqli_query($connection, $sql_check);

        if(!$result_check){
            $sql = "INSERT INTO `group_requests` (user_id, additional_details, group_id)
            VALUES ('$user_id', '$additional_details', '$group_id')";

            $result = mysqli_query($connection, $sql);

            if($result){
                Redirect("group_details?groupid=$group_id&success=Application Submited");
            } else {
                Redirect("group_details?groupid=$group_id&error=something went wrong");
            }

        } else {
            Redirect("group_details?groupid=$group_id&error=You have already submitted a request");
        }
    } else {
        Redirect("group_details?groupid=$group_id&error=You are already part of this group");
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
                            <h3>Apply to this group!</h3>
                            <h5>You can apply as long as you match the requirements. It's really simple!</h5>

                            <form method = "POST">
                                <div class="form-group">
                                    <label for="formGroupExampleInput">Full Name (this will work in place of your signature)</label>
                                    <input name = "full_name" type="text" class="form-control" id="formGroupExampleInput" placeholder="Full Name">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Details about yourself</label>
                                    <input name = "additional_details" type="text" class="form-control" id="formGroupExampleInput2" placeholder="Additional Details">
                                </div>
                                <!-- <div class="form-group">
                                    <label for="formGroupExampleInput2">Change Advisor Contact</label>
                                    <input name = "advisor_contact" type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input">
                                </div> -->
                                <!-- <div class="form-group">
                                    <label for="formGroupExampleInput2">Change Group Type</label>
                                    <br>
                                    <select name="group_type" id="" class="custom-select">
                                            <option value ="" >Please choose one of the following:</option>
                                            <option value="Honor Society">Honor Society</option>
                                            <option value="Club">Normal Group.</option>
                                    </select>
                                </div> -->
                                <!-- <div class="form-group">
                                    <label for="formGroupExampleInput2">Can this Group Tutor</label>
                                    <br>
                                    <select name="tutoring_services" id="" class="custom-select">
                                            <option value ="" >Please choose one of the following:</option>
                                            <option value="yes">Yes, this group may provide tutoring services</option>
                                            <option value="no">No, this group may not provide tutoring services</option>
                                    </select>
                                </div> -->
                                <br>
                                <button type="submit" class="btn btn-primary mb-2" name = "apply_to_group">I'd like to apply to join this group</button>
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
                  <!-- <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <br>
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:10px;">
                                <form method="POST">
                                <button type="submit" class="btn btn-success mb-25" name = "apply_group">Delete/Remove Group from Directory</button>
                                </form>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div> -->
<!--  -->
            </div>
        </div>
     </div>
