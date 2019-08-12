<?php include "../../templates/user_header.php";

$is_production = True;

//migrated from the admin_group_edit.php template file to here because it made more sense
$project_id = mysqli_real_escape_string($connection, $_GET['projectid']);
$sql = "SELECT * FROM `projects` WHERE project_id = '$project_id'";
    $result = mysqli_query( $connection, $sql );
    if($result){
            while ( $row = $result->fetch_assoc() ):
                $project_name = $row['project_name'];
                $requestor_email = $row['requestor_email'];
                $project_details = $row['project_details'];
                $initiated_by = $row['initiated_by'];
                $datetime_start = $row['datetime_start'];
                $type = $row['type'];
            endwhile;
} 

if(mysqli_num_rows( $result ) != 1){
    Redirect("add_service?error=Group Does Not Exist");
}

function find_groupid_from_name($group_name, $connection){
    $sql = "SELECT group_id FROM `groups_list` WHERE group_name = '$group_name'";
    $result = mysqli_query( $connection, $sql );
    if($result){
        $count = mysqli_num_rows( $result );
        if ( $count == 1 ) {
            while ( $row = $result->fetch_assoc() ):
                $group_id = $row['group_id'];
            endwhile;
            return $group_id;
        }
    } 
}

if ( isset( $_POST['delete'] ) & !empty( $_POST['delete'] ) ) {
    $sql2 = "DELETE from `users_in_projects` WHERE project_id = '$project_id' AND user_id = '$user_id'";
    $sql1 = "DELETE from `service_for_groups` WHERE project_id = '$project_id' AND user_id = '$user_id'";


    $result = mysqli_query($connection, $sql2);

    if($result){
        $result2 = mysqli_query($connection, $sql1);
        if($result2){
            Redirect("add_service?success=Project Membership Successfully Deleted");
        }
    }
    Redirect("add_service?error=Project Membership Unsuccessfully Deleted");
}


if ( isset( $_POST['add'] ) && !empty( $_POST['add'] ) ) {
    $service_hours = mysqli_real_escape_string( $connection, $_POST[ "service_hours" ] );
	$role = mysqli_real_escape_string( $connection, $_POST[ "role" ] );
    
    $sql_check = "SELECT * FROM `users_in_projects` WHERE user_id = '$user_id' AND project_id = '$project_id'";
    $resultcheck = mysqli_query( $connection, $sql_check );
    $num_rows = mysqli_num_rows($resultcheck);
    if($num_rows == 1){

        Redirect("add_service_specify?projectid=" . $project_id . "&error=Already registered in this project for that group. ");
    } else {

        //Adding hours to each group
        $checked = $_POST['affiliated_group_for_servicehours'];
        for($i=0; $i < count($checked); $i++){

            $new_group_id = find_groupid_from_name($checked[$i], $connection);
            

            $sql_check = "SELECT * FROM `service_for_groups` WHERE user_id = '$user_id' AND project_id = '$project_id' AND group_id = '$new_group_id'";
            $resultcheck = mysqli_query( $connection, $sql_check );
            $num_rows = mysqli_num_rows($resultcheck);
            if($num_rows == 0){

                echo $sql = "INSERT INTO `service_for_groups` (user_id, group_id, project_id) VALUES ('$user_id', '$new_group_id', '$project_id')";
                $result = mysqli_query( $connection, $sql );
                echo "Added ". $checked[$i];


            } else {
                echo "Already entered";
            }
        // echo "Selected " . $checked[$i] . "<br/>";
        }


        $sql = "INSERT INTO `users_in_projects` (user_id, project_id, role, service_hours) 
        VALUES ('$user_id', '$project_id', '$role', '$service_hours');";
        $result = mysqli_query( $connection, $sql );
        
        if ( $result ) {
            if(!$is_production){
                echo $group_id;
                echo "success";
            } else {
                Redirect("add_service_specify?projectid=" . $project_id . "&success=Service Hours successfully logged");
            }
            
        }

    }



}
//***************************** */


if ( isset( $_POST['edit'] ) && !empty( $_POST['edit'] ) ) {
    $service_hours = mysqli_real_escape_string( $connection, $_POST[ "service_hours" ] );
	$role = mysqli_real_escape_string( $connection, $_POST[ "role" ] );
    
    $sql_check = "SELECT * FROM `users_in_projects` WHERE user_id = '$user_id' AND project_id = '$project_id'";
    $resultcheck = mysqli_query( $connection, $sql_check );
    $num_rows = mysqli_num_rows($resultcheck);
    if($num_rows == 1){
        
        //updating hours to each group
        $sql1 = "DELETE from `service_for_groups` WHERE project_id = '$project_id' AND user_id = '$user_id'";
        $result = mysqli_query($connection, $sql1);

        $checked = $_POST['affiliated_group_for_servicehours'];

        for($i=0; $i < count($checked); $i++){

                $new_group_id = find_groupid_from_name($checked[$i], $connection);


                // $group_name = mysqli_real_escape_string( $connection, $checked[$i] );
                echo $sql = "INSERT INTO `service_for_groups` (user_id, group_id, project_id) VALUES ('$user_id', '$new_group_id', '$project_id')";
                $result = mysqli_query( $connection, $sql );
                echo "Added ". $checked[$i];
        // echo "Selected " . $checked[$i] . "<br/>";
        }


        $sql = "UPDATE `users_in_projects` SET role = '$role', service_hours = '$service_hours'
        WHERE user_id = '$user_id'AND  project_id = '$project_id'";

        $result = mysqli_query( $connection, $sql );
        
        if ( $result ) {
            if(!$is_production){
                echo $group_id;
                echo "success";
            } else {
                Redirect("add_service_specify?projectid=" . $project_id . "&success=Service Hours successfully logged");
            }
            
        }

    } else {
        Redirect("add_service_specify?projectid=" . $project_id . "&error=You are not entered into this project");
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

                                <p><?php echo "Project Name: ".  $project_name ?></p>
                                <p><?php echo "Requestor Email: ".  $requestor_email ?></p>
                                <p><?php echo "Project Details: ".  $project_details ?></p>
                                <!-- <p><?php echo "Added By: ".  $initiated_by ?></p> -->
                                <p><?php echo "Datetime Started: ".  $datetime_start ?></p>
                                <p><?php echo "Project View: ".  $type ?></p>

                                <hr>

                                <?php
                                            //finding what group leader role that student is in
                                            $studentdatasql = "SELECT service_for_groups.*, groups_list.* FROM `service_for_groups`, `groups_list`
                                            WHERE user_id = '$user_id' AND 
                                            project_id = '$project_id' AND service_for_groups.group_id = groups_list.group_id";

                                            $resultstudent = mysqli_query( $connection, $studentdatasql );

                                            if($resultstudent){
                                                echo "<div>";
                                                echo "<strong>Service Hours have been entered in for these groups: </strong> <br>";
                                                while ( $row2 = mysqli_fetch_assoc( $resultstudent ) ) {
                                                    echo $row2['group_name'] . "<br>";
                                                }
                                                echo "</div>";
                                                echo "<hr>";
                                            }
                                ?>

                                <?php
                                            //finding what group leader role that student is in
                                            $studentdatasql = "SELECT * FROM `groups`, `users_in_groups`,  `users` 
                                            WHERE groups.group_id = users_in_groups.group_id AND 
                                            users.user_id = users_in_groups.user_id AND is_group_leader = 'yes' AND users.user_id = '$user_id'";

                                            $resultstudent = mysqli_query( $connection, $studentdatasql );

                                            if($resultstudent){
                                                echo "<div>";
                                                echo "<strong>This student is a leader of the following:</strong> <br>";
                                                while ( $row2 = mysqli_fetch_assoc( $resultstudent ) ) {
                                                    echo $row2['group_name'] . "<br>";
                                                }
                                                echo "</div>";
                                                echo "<hr>";
                                            }
                                ?>

<div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-warning" > Current Project Members </h3>
                                <hr>
                                    <table class="table">
                                    <thead>
                                        <tr>
                                        <th>Student Number#</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT users.*, users_in_projects.* FROM users, users_in_projects WHERE users_in_projects.user_id = users.user_id 
                                        AND users_in_projects.project_id = '$project_id'";
                                        $result = mysqli_query( $connection, $sql );
                                        if($result){
                                                while ( $row = $result->fetch_assoc() ):
                                                    $student_name = $row['first_name'] . " " . $row['last_name'];
                                                    $studentid = $row['studentid'];
                                                    $list_users = $row['user_id'];
                                                    $role = $row['role'];
                                                    $service_hours = $row['service_hours'];
                                                    echo "
                                                    <tr class='table-dark'>
                                                    <td scope='row'>$studentid</td>
                                                    <td>$student_name</td>
                                                    <td>$role</td>
                                                    <td>$service_hours</td>
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


                  

                  <h3 class="list-group-item list-group-item-action list-group-item-success" >Add Service</h3>
                                <form method="POST">
													<br>
													<input type="number" name="service_hours" class="form-control" placeholder="Number of Service Hours" required maxlength = 100>
													<br>
													<input type="text" name="role" class="form-control" placeholder="My Role" required maxlength = 100>
													<br>
													
													<label for="">Add these service hours to which group?</label>
                                                    <br>
													<!-- <select name="affiliated_group_for_servicehours" id="">
													<option>Where do the hours count towards?</option>
                                                    </select> -->
                                                    <?php 
                                                    $sql = "SELECT groups_list.*, users_in_groups.* FROM `groups_list`, `users_in_groups` WHERE users_in_groups.user_id = '$user_id' AND users_in_groups.group_id = groups_list.group_id";
                                                    $result = mysqli_query( $connection, $sql );
                                                    if($result){                                    
                                                        while ( $row = mysqli_fetch_assoc( $result ) ) {
                                                            $group = $row[ 'group_name' ];

                                                            echo "
                                                            <input type='checkbox' name='affiliated_group_for_servicehours[]' value='$group'> $group
                                                            <br> 
                                                                ";
                                                        }
                                                    }
                                                    if(mysqli_num_rows($result) == 0){
                                                        echo"<label >You are not involved in any Groups (Honor Societies or Clubs). See Apply For Groups to the left.</label>";
                                                    }
                                                    ?>
													
													<br>
                                                    <br>

                                                    <strong>NOTE: Only enter hours for a service project into an Honor Society once. Please consult your Honor
                                                    Society regarding what service hours can be entered. </strong>
                                                    <br>
                                                    <br>
                                                    <strong>If you want to submit toward multiple groups, please resubmit to this form.</strong>

                                                    <br>
													<br>
													<button class="btn btn-success" name='add' type="submit" value='add'>Add Me To this Project</button>
									</form>    
                                                <br>     
                                                <hr>

                                    
                  

                  <h3 class="list-group-item list-group-item-action list-group-item-warning" > Edit My Service Details </h3>
                                <form method="POST">
													<br>
													<input type="number" name="service_hours" class="form-control" placeholder="Number of Service Hours" required maxlength = 100>
													<br>
													<input type="text" name="role" class="form-control" placeholder="My Role" required maxlength = 100>
													<br>

													<label for="">Add these service hours to which group?</label>
                                                    <br>
													<!-- <select name="affiliated_group_for_servicehours" id="">
													<option>Where do the hours count towards?</option>
                                                    </select> -->
                                                    <?php 
                                                    $sql = "SELECT groups_list.*, users_in_groups.* FROM `groups_list`, `users_in_groups` WHERE users_in_groups.user_id = '$user_id' AND users_in_groups.group_id = groups_list.group_id";
                                                    $result = mysqli_query( $connection, $sql );
                                                    if($result){                                    
                                                        while ( $row = mysqli_fetch_assoc( $result ) ) {
                                                            $group = $row[ 'group_name' ];

                                                            echo "
                                                            <input type='checkbox' name='affiliated_group_for_servicehours[]' value='$group'> $group
                                                            <br> 
                                                                ";
                                                        }
                                                    }
                                                    ?>
													
													<br>
                                                    <br>

                                                    <strong>NOTE: All Values must be updated when you submit an edit. If something has not changed, retype the information in.</strong>
                                                    <br>
                                                    <br>
													<button class="btn btn-success" name='edit' type="submit" value='edit'>Edit My Service</button>
									</form>    
                                                <br>     
                                                <hr>
                                                <!-- Only Honor Society Members can Edit Projects -->
                                    <strong>By clicking this you are effectively removing your involvement from this service project. You will still be able to re-enter service. </strong>
                                    <br>
                                    <br>
                                   <form method="POST">
                                   <button class="btn btn-danger" name='delete' type="submit" value='delete'>Remove me from this project</button>
                                   </form>
                                    <br>
                                    <br>
                                    <hr>

<!-- Only Honor Society Members can Edit Projects -->

<?php if($is_honor_society_member){ ?>

                                    <strong>Any Honor Society Member can edit project details. Please be careful and wise with your decisions.</strong>
                                    <br>
                                    <br>
                                    <?php $str = 'edit_project_details.php?projectid=' . $project_id ?>
                                    <a href="<? echo  $str?>" class="btn btn-warning" >Edit the Project Details</a>
                                    <br>
                                    <br>

<?php }?>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>