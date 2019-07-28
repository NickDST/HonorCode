<?php include "../../templates/admin_header.php";

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
    </div>
</div>