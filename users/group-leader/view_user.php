<?php include "../../templates/group_leader.php";

$user_id = mysqli_real_escape_string($connection, $_GET['userid']);

$sql = "SELECT * FROM `users` where user_id = '$user_id'";

$result = mysqli_query( $connection, $sql );
if($result){
        while ( $row = $result->fetch_assoc() ):
            $username = $row['username'];
            $password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $email = $row['email'];
            $graduate_by = $row['graduate_by'];
            $studentid = $row['studentid'];
            $date_created = $row['date_created'];
        endwhile;
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

                                <p><?php echo "Username: ".  $username ?></p>
                                <p><?php echo "email: ".  $email ?></p>
                                <p><?php echo "accounted created: ".  $date_created ?></p>
                                <p><?php echo "Year: ".  "N/A" ?></p>
                                <p><?php echo "Graduation: ".  $graduate_by ?></p>
                                <p><?php echo "Student Number: ".  $studentid ?></p>

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

                                
                            </div>
                </div>
            </div>
        </div>
        <!--  -->

        <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-success" > Statistics </h3>
                                <hr>
                                <strong>Total Hours for this Group</strong>

                                <?php

                                    $studentdatasql = "SELECT * FROM `users_in_groups`
                                    WHERE user_id = '$user_id' AND group_id = '$group_id'";

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
    
        <!--  -->
        <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-warning" > Service List </h3>
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
                                                    <td><a class = 'btn btn-primary' href = '../service/add_service_specify?projectid=$project_id'> Project Details + Edit </a></td>
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





    </div>
</div>