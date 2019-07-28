<?php include "../../templates/group_leader.php";
// $group_id =  $_SESSION['group_leader'];
$is_production = false;

$user_email = '';

if(isset($_POST['grant_membership'])){
    $user = mysqli_real_escape_string($connection, $_POST['grant_membership']);

    // grant membership to this user, change pending status, insert user to the user_in_groups table, send an email
    $sql = "UPDATE `group_requests`
    SET request_status = 'admitted'
    WHERE group_id = '$group_id' AND user_id='$user' ";

    $result = mysqli_query($connection, $sql);

    if ($result){
        $sql2 = "INSERT INTO users_in_groups (user_id, group_id)
        VALUES ('$user', '$group_id');";
        $result2 = mysqli_query($connection, $sql2);

        if($result2){

            $sql3 = "SELECT * FROM `users` WHERE user_id = '$user'";
            $result3 = mysqli_query($connection, $sql3);
            if($result3){
                while ( $row = mysqli_fetch_assoc( $result3 ) ) {
                    $user_email = $row[ 'email' ];
                }
            }
            $recipients = array($nhs_email, $user_email, $GL_email);
            $subject = "Membership - $group_name";
            $content = "<strong> Membership for $group_name has been granted! </strong>";

            $email = tutorsEmail($recipients, $subject, $content);
            if($email){
                Redirect('membership_requests?success=Member has been Granted');
            }
            
        }

    }

}


if(isset($_POST['deny_membership'])){
    $user = mysqli_real_escape_string($connection, $_POST['deny_membership']);

    // deny membership to this user, change pending status, insert user to the user_in_groups table, send an email
        // grant membership to this user, change pending status, insert user to the user_in_groups table, send an email
        echo $sql = "UPDATE `group_requests`
        SET request_status = 'denied'
        WHERE group_id = '$group_id' AND user_id='$user' ";
    
        $result = mysqli_query($connection, $sql);
    
        if ($result){
            //Send an email to recipient
            $sql3 = "SELECT * FROM `users` WHERE user_id = '$user'";
            $result3 = mysqli_query($connection, $sql3);
            if($result3){
                while ( $row = mysqli_fetch_assoc( $result3 ) ) {
                    $user_email = $row[ 'email' ];
                }
            }
            $recipients = array($nhs_email, $user_email, $GL_email);
            $subject = "Membership - $group_name";
            $content = "<strong> Membership for $group_name has been denied. Please email the Leader of this group for further details.  </strong>";

            $email = tutorsEmail($recipients, $subject, $content);
            if($email){
                Redirect('membership_requests?success=Member has been Denied');
            }
        }
}


function initial_load($connection, $group_id){
	$sql = "SELECT group_requests.*, users.* FROM `group_requests`, `users` WHERE group_id = '$group_id' AND group_requests.user_id = users.user_id AND group_requests.request_status = 'pending' LIMIT 20";
                                $result = mysqli_query( $connection, $sql );
                                if($result){
									while ( $row = mysqli_fetch_assoc( $result ) ) {

                                        $user_email = $row[ 'email' ];



										echo "
                                    <div>
                                    <h3>" . $row[ 'first_name' ] . " " . $row[ 'last_name' ] ." </h3>
                                        <p> Status: " . $row[ 'request_status' ] . "</p>
                                        <p>Additional Information: " . $row[ 'additional_details' ] . "</p>
                                        <p> Email: " . $row[ 'email' ] . "</p>
                                        <p> Application Time: " . $row[ 'date_submitted' ] . "</p>
                                        <form method='POST'>
                                            <button name = 'grant_membership' value=".$row['user_id'] ." class='btn btn-success' type='submit'>Grant Membership</button>
                                        </form>
                                          
                                        <br>
                                        <form method='POST'>
                                            <button name = 'deny_membership' value=".$row['user_id'] ." class='btn btn-danger' type='submit'>Deny Membership</button>
                                        </form>
                                    </div>
                                    <hr>";
									}
								} else {
									if(!$is_production){
										echo $sql;
									}
									echo "No Groups created yet";
                                }
}


function search_load($connection, $search, $group_id){
    $sql = "SELECT group_requests.*, users.* FROM `group_requests`, `users` WHERE 
             (users.user_id LIKE '%$search%' OR users.username LIKE '%$search%') AND users.user_id = group_requests.user_id AND group_requests.group_id = '$group_id' AND group_requests.request_status = 'pending'";
			
	$result = mysqli_query( $connection, $sql );
	$queryResults = mysqli_num_rows( $result );
	if ( $queryResults > 0 ) {
		while ( $row = mysqli_fetch_assoc( $result ) ) {
			echo "
                                    <div>
                                        <h3>" . $row[ 'first_name' ] . " " . $row[ 'last_name' ] ." </h3>
                                        <p> Status: " . $row[ 'request_status' ] . "</p>
                                        <p> Additional Information: " . $row[ 'additional_details' ] . "</p>
                                        <p> Email: " . $row[ 'email' ] . "</p>
                                        <a class = 'btn btn-success' href = 'individual_Details?userid=".$row['user_id']."'>Grant Membership</a>
                                        <br>
                                        <br>
                                        <a class = 'btn btn-danger' href = 'individual_Details?userid=".$row['user_id']."'>Deny Membership</a>
                                    </div>
                                    <hr>";
		}
	} else {
        echo "<p> No Results </p>";
    }
}

// echo $nhs_email;
// echo $user_email;
// echo $GL_email;

?>

<div class="content">
		<div class="container-fluid">
			<div class="row">

				<div class="col-md-12">
					<div class="card">

						<div class="header">
                        <div class="jumbotron jumbotron-fluid">

                            <h2 class="title"><strong>Honor Groups</strong></h2>
                            <p class="lead">View Groups and their details.</p>
							<h3><a class = 'btn btn-success' href="add_group">Add/Create a new Group</a></h3>
                        </div>	
						</div>
						<div class="" style="padding-left:15px; padding-right:15px">
							<form method="POST">

                            <div class="form-group">
                            <label for="formGroupExampleInput"><h3>Search for Existing Group</h3></label>
                            <input type="text" name="search_user" placeholder="Search" class = "form-control" maxlength=100>
                            </div>
								<button type="submit" name="asdfhlasdk" class = 'btn btn-success'>Search</button>
							</form>
							<div class="article-container">
								<hr>
								<?php
                                if(!isset($_POST['search_user'])){
                                    initial_load($connection, $group_id);
                                } else {
                                    $search = mysqli_real_escape_string($connection, $_POST['search_user']);
                                    search_load($connection, $search, $group_id);
                                }
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



 
