<?php include "../../templates/group_leader.php";



?>

<div class="content">
            <div class="container-fluid">

            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-warning" > Select Timeframe </h3>
                              <form method="POST">
                                <br>
                                <label for="">Date Started (use Chrome)</label>
                                <input type="date" class="form-control" name="date_started">
                                <br>
                                <label for="">Date Ended (use Chrome)</label>
                                <input type="date" class="form-control" name="date_ended">
                                <br>
                                <br>
                                <button name="submit" class="btn btn-success" type="submit">Submit</button>
                              </form>

                              <br>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div>



            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-warning" > Specific Timeframe (Fill out the form above) </h3>
                                <hr>
                                    <table class="table">
                                    <thead>
                                        <tr>
                                        <th>Student Name</th>
                                        <th>Total Hours in this Group</th>
                                        <th>Number of Projects (including tutoring)</th>
                                        <th>Number of Tutoring Events</th>
                                        <th>View Student</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php



                                if(isset($_POST['submit'])){

                                        //******* */
                                        $date_started = mysqli_real_escape_string( $connection, $_POST[ 'date_started' ] );
                                        $date_ended = mysqli_real_escape_string( $connection, $_POST[ 'date_ended' ] );

                                        echo "Service from $date_started to $date_ended";

                                        $sql3 = "SELECT users.*, users_in_groups.* FROM users, users_in_groups WHERE users.user_id = users_in_groups.user_id 
                                        AND users_in_groups.group_id = '$group_id'";
                                        $result3 = mysqli_query($connection, $sql3);
                                        if($result3){
                                            while ( $row2 = mysqli_fetch_assoc( $result3 ) ) {
                                                $specific_user_id = $row2['user_id'];
                                                $this_user_name = $row2['first_name'] . " " . $row2['last_name'];

                                                $sql4 = "SELECT users_in_projects.* , users_in_groups.*, projects.* FROM users_in_projects, users_in_groups, projects
                                                WHERE users_in_projects.user_id = users_in_groups.user_id AND users_in_groups.group_id = '$group_id' AND users_in_projects.user_id = '$specific_user_id' AND projects.project_id = users_in_projects.project_id 
                                                AND (projects.datetime_start BETWEEN '$date_started' AND '$date_ended' ) ";

                                                $total_service = 0; //important
                                                $result4 = mysqli_query($connection, $sql4);
                                                    if($result4){
                                                        while ( $row4 = mysqli_fetch_assoc( $result4 ) ) {
                                                            $total_service = $total_service + $row4['service_hours'];
                                                        }
                                                    }

                                                $sql5 = "SELECT users_in_projects.* , users_in_groups.*, projects.* FROM users_in_projects, users_in_groups, projects
                                                WHERE users_in_projects.user_id = users_in_groups.user_id AND users_in_groups.group_id = '$group_id' AND users_in_projects.user_id = '$specific_user_id' AND projects.project_id = users_in_projects.project_id AND projects.tutoring_event = 'yes'
                                                AND (projects.datetime_start BETWEEN '$date_started' AND '$date_ended' )  ";

                                                $num_tutor = 0;  //important
                                                $result5 = mysqli_query($connection, $sql5);
                                                    if($result5){
                                                        while ( $row5 = mysqli_fetch_assoc( $result5 ) ) {
                                                            $num_tutor = $num_tutor + 1;
                                                        }
                                                    }

                                                $sql5 = "SELECT users_in_projects.* , users_in_groups.*, projects.* FROM users_in_projects, users_in_groups, projects
                                                WHERE users_in_projects.user_id = users_in_groups.user_id AND users_in_groups.group_id = '$group_id' AND users_in_projects.user_id = '$specific_user_id' AND projects.project_id = users_in_projects.project_id 
                                                AND (projects.datetime_start BETWEEN '$date_started' AND '$date_ended' ) ";
    
                                                $num_projects = 0; //important
                                                $result4 = mysqli_query($connection, $sql4);
                                                    if($result4){
                                                        while ( $row4 = mysqli_fetch_assoc( $result4 ) ) {
                                                            $num_projects = $num_projects + 1;
                                                        }
                                                    }

                                                    echo "
                                                    <tr class='table-dark'>
                                                    <th scope='row'>$this_user_name</th>
                                                    <td>$total_service</td>
                                                    <td>$num_projects</td>
                                                    <td>$num_tutor</td>
                                                    <td><a class = 'btn btn-primary' href = 'view_user?userid=$specific_user_id'> View Details </a></td>
                                                    </tr>
                                                    ";
                                            }
                                        }

                                    } 
                                    ?>
                                    </tbody>
                                    </table>
                                    <!-- <a class = 'btn btn-primary' href = 'add_group_leaders?groupid=<?php echo $group_id ?>'> Add Group Leaders</a> -->
                                    <br>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-warning" >General Service (Everything)</h3>
                                <hr>
                                    <table class="table">
                                    <thead>
                                        <tr>
                                        <th>Student Name</th>
                                        <th>Total Hours in this Group</th>
                                        <th>Number of Projects (including tutoring)</th>
                                        <th>Number of Tutoring Events</th>
                                        <th>View Student</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php


                                            $sql3 = "SELECT users.*, users_in_groups.* FROM users, users_in_groups WHERE users.user_id = users_in_groups.user_id 
                                            AND users_in_groups.group_id = '$group_id'";
                                            $result3 = mysqli_query($connection, $sql3);
                                            if($result3){
                                                while ( $row2 = mysqli_fetch_assoc( $result3 ) ) {
                                                    $specific_user_id = $row2['user_id'];
                                                    $this_user_name = $row2['first_name'] . " " . $row2['last_name'];
    
                                                    $sql4 = "SELECT users_in_projects.* , users_in_groups.* FROM users_in_projects, users_in_groups 
                                                    WHERE users_in_projects.user_id = users_in_groups.user_id AND users_in_groups.group_id = '$group_id' AND users_in_projects.user_id = '$specific_user_id' ";
    
                                                    $total_service = 0; //important
                                                    $result4 = mysqli_query($connection, $sql4);
                                                        if($result4){
                                                            while ( $row4 = mysqli_fetch_assoc( $result4 ) ) {
                                                                $total_service = $total_service + $row4['service_hours'];
                                                            }
                                                        }
    
                                                    $sql5 = "SELECT users_in_projects.* , users_in_groups.*, projects.* FROM users_in_projects, users_in_groups, projects
                                                    WHERE users_in_projects.user_id = users_in_groups.user_id AND users_in_groups.group_id = '$group_id' AND users_in_projects.user_id = '$specific_user_id' AND projects.project_id = users_in_projects.project_id AND projects.tutoring_event = 'yes' ";
    
                                                    $num_tutor = 0;  //important
                                                    $result5 = mysqli_query($connection, $sql5);
                                                        if($result5){
                                                            while ( $row5 = mysqli_fetch_assoc( $result5 ) ) {
                                                                $num_tutor = $num_tutor + 1;
                                                            }
                                                        }
    
                                                    $sql5 = "SELECT users_in_projects.* , users_in_groups.*, projects.* FROM users_in_projects, users_in_groups, projects
                                                    WHERE users_in_projects.user_id = users_in_groups.user_id AND users_in_groups.group_id = '$group_id' AND users_in_projects.user_id = '$specific_user_id' AND projects.project_id = users_in_projects.project_id ";
        
                                                    $num_projects = 0; //important
                                                    $result4 = mysqli_query($connection, $sql4);
                                                        if($result4){
                                                            while ( $row4 = mysqli_fetch_assoc( $result4 ) ) {
                                                                $num_projects = $num_projects + 1;
                                                            }
                                                        }
    
                                                        echo "
                                                        <tr class='table-dark'>
                                                        <th scope='row'>$this_user_name</th>
                                                        <td>$total_service</td>
                                                        <td>$num_projects</td>
                                                        <td>$num_tutor</td>
                                                        <td><a class = 'btn btn-primary' href = 'view_user?userid=$specific_user_id'> View Details </a></td>
                                                        </tr>
                                                        ";
                                                }
                                            }
    




                                        ?>




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