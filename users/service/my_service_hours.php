<?php include "../../templates/user_header.php";

//include "../../templates/admin_group_edit.php";
$is_production = true;

//migrated from the admin_group_edit.php template file to here because it made more sense
// $group_id = mysqli_real_escape_string($connection, $_GET['groupid']);
$sql = "SELECT * FROM `users` WHERE user_id = '$user_id'";
    $result = mysqli_query( $connection, $sql );
    if($result){
            while ( $row = $result->fetch_assoc() ):
                $username = $row['username'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $studentid = $row['studentid'];
                $email = $row['email'];
                $date_created = $row['date_created'];
            endwhile;
} 



// //for when deleting a group
// if(isset($_POST['remove_group'])){
//     $sql1 = "DELETE from `groups_list` WHERE group_id = '$group_id'";
//     $sql2 = "DELETE from `users_in_groups` WHERE group_id = '$group_id'";

//     $result = mysqli_query($connection, $sql2);

//     if($result){
//         $result2 = mysqli_query($connection, $sql1);
//         if($result2){

//             Redirect("manage_groups?success=Group Successfully Deleted");
//         }
//         Redirect("manage_groups?error=Group Unsuccessfully Deleted");
//     }
//     Redirect("manage_groups?error=Group Membership Unsuccessfully Deleted");

// }

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
                              <h3 class="list-group-item list-group-item-action list-group-item-info" ><?php echo $first_name . " " . $last_name ?></h3>
                                <hr>
                                <p><strong><?php echo $username ?></strong></p>
                                <p>Contact: <?php echo $email ?></p>
                                <p>Date Created: <?php echo $date_created ?></p>
                                <p>StudentID: <?php echo $studentid ?></p>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-success" > Statistics </h3>
                                <hr>
                                <strong>Affiliated Groups and Respective Total Hours</strong>

                                <?php

                                    $studentdatasql = "SELECT * FROM `users_in_groups`
                                    WHERE user_id = '$user_id'";

                                    $resultstudent = mysqli_query( $connection, $studentdatasql );

                                    if($resultstudent){
                                        while ( $row2 = mysqli_fetch_assoc( $resultstudent ) ) {
                                            $total_hours = 0;
                                            $group_id = $row2['group_id'];

                                            $sql3 = "SELECT * FROM `groups_list` WHERE group_id = '$group_id'";
                                            
                                            $result3 = mysqli_query( $connection, $sql3 );
                                            while ( $row4 = mysqli_fetch_assoc( $result3 ) ) {
                                                $group_name = $row4['group_name'];
                                            }

                                            $sql2 = "SELECT users_in_projects.*, service_for_groups.*, groups_list.* FROM `users_in_projects`, `service_for_groups` , `groups_list`
                                            WHERE users_in_projects.project_id = service_for_groups.project_id AND service_for_groups.group_id = groups_list.group_id
                                            AND users_in_projects.user_id = service_for_groups.user_id AND users_in_projects.user_id = '$user_id' AND groups_list.group_id = '$group_id'";                                            


                                            $result2 = mysqli_query( $connection, $sql2 );
                                            while ( $row3 = mysqli_fetch_assoc( $result2 ) ) {
                                            
                                                $total_hours = $total_hours + $row3[service_hours];
                                            }

                                            echo "<h3> Group: $group_name </h3>";
                                            echo "<p> Total Hours:  $total_hours </p>";
                                            // echo $total_hours;
                                            echo "<hr>";
                                        }
                                    }    

                                ?>


                               
                            </div>
                        </div>
                    </div>
                  </div>
                  
                  <!-- <div class="row">
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
                    </div> -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-warning" > My Service </h3>
                                <hr>
                                    <table class="table">
                                    <thead>
                                        <tr>
                                        <th>Project Name</th>
                                        <th>Hours</th>
                                        <th>Role</th>
                                        <th>Project Hours Count Towards:</th>
                                        <th>Project Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                        $sql = "SELECT users_in_projects.*, projects.* FROM users_in_projects, projects WHERE users_in_projects.user_id = '$user_id' 
                                        AND users_in_projects.project_id = projects.project_id";
                                        $result = mysqli_query( $connection, $sql );
                                        if($result){
                                                while ( $row = $result->fetch_assoc() ):
                                                    $project_name = $row['project_name'];
                                                    $service_hours = $row['service_hours'];
                                                    $project_id = $row['project_id'];
                                                    $role = $row['role'];

                                                     //finding what group leader role that student is in
                                                     $studentdatasql = "SELECT service_for_groups.*, groups_list.* FROM `service_for_groups`, `groups_list`
                                                     WHERE user_id = '$user_id' AND 
                                                     project_id = '$project_id' AND service_for_groups.group_id = groups_list.group_id";
                                            $count_towards = ""; 

                                            $resultstudent = mysqli_query( $connection, $studentdatasql );

                                            if($resultstudent){
                                                while ( $row2 = mysqli_fetch_assoc( $resultstudent ) ) {
                                                    //echo $row2['group_name'] . "<br>";
                                                    $count_towards = $count_towards . $row2['group_name'] . "<br>";
                                                }
                                            }    

                                                    echo "
                                                    <tr class='table-dark'>
                                                    <th scope='row'>$project_name</th>
                                                    <td>$service_hours</td>
                                                    <td>$role</td>
                                                    <td>$count_towards</td>
                                                    <td><a class = 'btn btn-primary' href = 'add_service_specify?projectid=$project_id'> Project Details + Edit </a></td>
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
                                <button type="submit" class="btn btn-danger mb-25" name = "remove_group">Delete/Remove Group from Directory</button>
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
