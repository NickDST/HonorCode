<?php include "../../templates/user_header.php";

//TODO: This is the hub for managing the tutor requests
if(isset($_POST['accept_tutoring'])){

    $request_id = mysqli_real_escape_string($connection, $_POST['accept_tutoring']);
    $sql = "UPDATE `tutor_requests` SET is_processed = 'accepted' WHERE tutor_request_id = '$request_id'";
    $result = mysqli_query($connection, $sql);
    if($result){
        //Emailing will take place here
        echo "ACCEPTED Emailing both the person requesting and the details";
    }
}


if(isset($_POST['deny_tutoring'])){
    $request_id = mysqli_real_escape_string($connection, $_POST['deny_tutoring']);
    $deny_reason = mysqli_real_escape_string($connection, $_POST['deny_reason']);

    $sql = "UPDATE `tutor_requests` SET is_processed = 'denied' WHERE tutor_request_id = '$request_id'";
    $result = mysqli_query($connection, $sql);
    if($result){
        //Emailing will take place here
        echo "DENIED Emailing both the person requesting and " . $deny_reason;
    }
}


if(isset($_POST['set_tutoring'])){
    $sql = "DELETE FROM `users_in_subjects` WHERE user_id = '$user_id'";
    $result = mysqli_query($connection, $sql);
    if($result){

        $checked = $_POST['tutorsubjects'];
        for($i=0; $i < count($checked); $i++){
            $subject_id = mysqli_real_escape_string($connection, $checked[$i]);

            $sql = "INSERT INTO `users_in_subjects` (user_id, subject_id) VALUES ($user_id, $subject_id)";
            $result = mysqli_query($connection, $sql);
            if($result){
                //echo "Submitted";
            }
            
        }
    }
}

?>


<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-info" >Set Available Times</h3>
                                <hr>
                                <p><strong>Follow the Instructions on the right side and set your available times accordingly</strong></p>
                                <p>Please refer to this video on how to set available times. </p>
                                <a href="availability" class = "btn btn-primary">Click here to Set Availability</a>
                                <br>
                                <hr>
                                <h4>Subjects Currently Set to Tutor:</h4>
                                <?php
                                    $sql = "SELECT users_in_subjects.*, tutor_subjects.* FROM users_in_subjects, tutor_subjects WHERE users_in_subjects.subject_id = tutor_subjects.subject_id AND users_in_subjects.user_id = '$user_id'";
                                    $result = mysqli_query($connection, $sql);
                                    if(mysqli_num_rows($result) > 0){
                                        while ( $row = mysqli_fetch_assoc( $result ) ) {
                                            echo $row['subject_name'];
                                            echo "<br>";
                                        }
                                    } else {
                                        echo "No Subjects Currently set to Tutor";
                                    }

                                ?>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-success" >Pending Requests</h3>
                                <hr>
                               <?php
                                    $sql = "SELECT * FROM `tutor_requests` WHERE user_id = '$user_id' AND is_processed = 'no'";
                                	//$sql = "SELECT group_requests.*, users.* FROM `group_requests`, `users` WHERE group_id = '$group_id' AND group_requests.user_id = users.user_id AND group_requests.request_status = 'pending' LIMIT 20";
                                    $result = mysqli_query( $connection, $sql );
                                    if($result){
                                        while ( $row = mysqli_fetch_assoc( $result ) ) {
    
                                            $request_id = $row[ 'tutor_request_id' ];
    
                                            echo "
                                        <div>
                                        <h3>" . $row[ 'requestor_name' ]  ." </h3>
                                            <p> Contact: " . $row[ 'requestor_contact' ] . "</p>
                                            <p>Additional Information: " . $row[ 'tutor_details' ] . "</p>
                                            <p> Datetime Start: " . $row[ 'datetime_start' ] . "</p>
                                            <p> Datetime End: " . $row[ 'datetime_end' ] . "</p>
                                            <p> Request Submitted: " . $row[ 'application_time' ] . "</p>
                                            <form method='POST'>
                                                <button name = 'accept_tutoring' value=".$row['tutor_request_id'] ." class='btn btn-success' type='submit'>Accept the Tutoring Request</button>
                                            </form>
                                              <hr>
                                            <form method='POST'>
                                                <label >If rejecting the request, please explain why. </label>
                                                <input name='deny_reason' type='text' class= 'form-control' placeholder = 'If rejecting the request, please explain why. ' required>
                                                <br>
                                                <button name = 'deny_tutoring' value=". $row['tutor_request_id'] ." class='btn btn-danger' type='submit'>Reject the Tutoring Request</button>
                                            </form>
                                        </div>
                                        <hr>";
                                        }
                                    } else {

                                        if(!$is_production){
                                            echo $sql;
                                        }
                                        
                                    }

                                    if(mysqli_num_rows($result) == 0){
                                        echo "<p>No Requests Yet. Tutoring Requests will show up here. </p>";
                                    }
                               ?>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class = "" style= "padding-left:15px; padding-right:15px; padding-top:5px;">
                              <h3 class="list-group-item list-group-item-action list-group-item-warning" >Set Tutoring Subjects</h3>
                                <hr>
                                <!-- <input type='checkbox' name='tutorsubjects[]' value=''> -->
                                <strong>Select the Subjects you are willing to teach and submit. </strong>
                                <br>
                                <br>
                                <form method="POST">
                                <?php
                                $sql = "SELECT * FROM `tutor_subjects`";
                                $result = mysqli_query( $connection, $sql );
                                if($result){
                                    while ( $row = mysqli_fetch_assoc( $result ) ) {
                                        $subject_name = $row['subject_name'];
                                        $subject_id = $row['subject_id'];
                                        echo "
                                        <input type='checkbox' name='tutorsubjects[]' value='$subject_id'> $subject_name

                                        <br>
                                        ";
                                    }
                                }
                                

                                ?>
                                <br>
                                <button type="submit" class="btn btn-warning" name="set_tutoring">Set Tutoring Subjects</button>

                                </form>
                                <br>
                            </div>
                        </div>
                    </div>
                  </div>

    </div>
</div>