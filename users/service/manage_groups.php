<?php include "../../templates/user_header.php";

$is_production = false;

function initial_load($connection, $user_id){
	$sql = "SELECT groups_list.*, users_in_groups.* FROM `groups_list`, `users_in_groups` WHERE users_in_groups.group_id = groups_list.group_id AND users_in_groups.user_id = '$user_id' ";
                                $result = mysqli_query( $connection, $sql );
                                if($result){
									while ( $row = mysqli_fetch_assoc( $result ) ) {
										echo "
                                    <div>
                                        <h3>" . $row[ 'group_name' ] . "</h3>
                                        <p> Group Descriptions: " . $row[ 'group_description' ] . "</p>
                                        <p> Advisor Contact: " . $row[ 'advisor_contact' ] . "</p>
                                        <p> Provides Tutoring (if you see 0 it means no): " . $row[ 'tutoring_services' ] . " </p>
                                        <p> Date Joined: " . $row[ 'date_added' ] . "</p>
										
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


function search_load($connection, $search){
    $sql = "SELECT * FROM `groups` WHERE 
             (group_name LIKE '%$search%' OR advisor_contact LIKE '%$search%' OR group_type LIKE '%$search%' OR group_description)";
			
	$result = mysqli_query( $connection, $sql );
	$queryResults = mysqli_num_rows( $result );
	if ( $queryResults > 0 ) {
		while ( $row = mysqli_fetch_assoc( $result ) ) {
			echo "
		<div>
			<h3>" . $row[ 'group_name' ] . "</h3>
			<p>" . $row[ 'group_description' ] . "</p>
			<a class = 'btn btn-secondary' href = 'group_details?groupid=".$row['group_id']."'>More Info/Apply</a>
		</div>
		<hr>";
		}
	} else {
        echo "<p> No Results </p>";
    }
}
?>


<div class="content">
		<div class="container-fluid">
			<div class="row">

				<div class="col-md-12">
					<div class="card">

						<div class="header">
                        <div class="jumbotron jumbotron-fluid">

                            <h2 class="title"><strong>My Groups</strong></h2>
                            <p class="lead">View Groups and their details.</p>
							<!-- <h3><a class = 'btn btn-warning' href="pending_requests">View Pending Requests</a></h3> -->
                        </div>	
						</div>
						<div class="" style="padding-left:15px; padding-right:15px">
							<!-- <form method="POST">

                            <div class="form-group">
                            <label for="formGroupExampleInput"><h3>Search for Existing Group</h3></label>
                            <input type="text" name="search_user" placeholder="Search" class = "form-control" maxlength=100>
                            </div>
								<button type="submit" name="asdfhlasdk" class = 'btn btn-success'>Search</button>
							</form> -->
							<div class="article-container">
								<hr>
                                <?php
                                initial_load($connection, $user_id);
                                // if(!isset($_POST['search_user'])){
                                //     initial_load($connection);
                                // } else {
                                //     $search = mysqli_real_escape_string($connection, $_POST['search_user']);
                                //     search_load($connection, $search);
                                // }
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



 
