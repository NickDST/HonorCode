<?php include "../../templates/user_header.php";



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
                $datetime_started = $row['datetime_started'];
                $type = $row['type'];
            endwhile;
} 

if(mysqli_num_rows( $result ) != 1){
    Redirect("add_service?error=Group Does Not Exist");
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
                                <p><?php echo "Year: ".  "N/A" ?></p>
                                <p><?php echo "Added By: ".  $initiated_by ?></p>
                                <p><?php echo "Datetime Started: ".  $datetime_started ?></p>

                                <hr>

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

                                            <h2>Enter Service</h2>
                                <form method="post">
													<br>
													<input type="number" name="service_hours" class="form-control" placeholder="Number of Service Hours" required maxlength = 100>
													<br>
													<input type="text" name="role" class="form-control" placeholder="My Role" required maxlength = 100>
													<br>
													
													<select name="affiliated_group_for_servicehours" id="">
													<option>Where do the hours count towards?</option>
                                                    <?php 
                                                    $sql = "SELECT groups_list.*, users_in_groups.* FROM `groups_list`, `users_in_groups` WHERE users_in_groups.user_id = '$user_id' AND users_in_groups.group_id = groups_list.group_id";
                                                    $result = mysqli_query( $connection, $sql );
                                                    if($result){                                    
                                                        while ( $row = mysqli_fetch_assoc( $result ) ) {
                                                            $group = $row[ 'group_name' ];

                                                            echo "
                                                            <option value='$group'>$group</option>
                                                                ";
                                                        }
                                                    }
                                                    ?>
													</select>
													<br>
                                                    <br>

                                                    <strong>NOTE: Only enter hours for a service project into an Honor Society once. Please consult your Honor
                                                    Society regarding what service hours can be entered. </strong>

                                                    <br>
													<br>
													<button class="btn btn-success" name='add' type="submit" value='add'>Add Me To this Project</button>
									</form>    
                                                <br>     
                                                <hr>

<!-- Only Honor Society Members can Edit Projects -->
                                    <strong>Any Honor Society Member can edit project details. Please be careful and wise with your decisions.</strong>
                                    <br>
                                    <br>
                                    <?php $str = 'edit_project_details.php?projectid=' . $project_id ?>
                                    <a href="<? echo  $str?>" class="btn btn-warning" >Edit the Project Details</a>
                                    <br>
                                    <br>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>