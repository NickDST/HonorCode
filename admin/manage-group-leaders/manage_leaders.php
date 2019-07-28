<?php include "../../templates/admin_header.php";

$is_production = false;

function initial_load($connection){
    //finding users that are group Leaders
    $sql = "SELECT DISTINCT users.user_id FROM `groups_list`, `users_in_groups`,  `users` 
    WHERE groups_list.group_id = users_in_groups.group_id AND 
    users.user_id = users_in_groups.user_id AND is_group_leader = 'yes'";


                                $result = mysqli_query( $connection, $sql );

                                if($result){
									while ( $row = mysqli_fetch_assoc( $result ) ) {

                                        $user_id = $row['user_id'];


                                        $sql = "SELECT * FROM `users` where user_id = '$user_id'";
                                        $studentdataresult = mysqli_query( $connection, $sql );
                                        if($studentdataresult){
                                            while ( $row = mysqli_fetch_assoc( $studentdataresult ) ) {
                                                echo "<h2>". $row['username'] . "</h2>";
                                                echo "<h3>full name: " . $row['first_name'] ." ". $row['last_name'] . "</h3>";
                                                echo "            <a class = 'btn btn-warning' href = 'view_user?userid=".$row['user_id'] . "'> View Profile </a>
                                                ";
                                            }
                                        }


                                            //finding what group leader role that student is in
                                            $studentdatasql = "SELECT * FROM `groups_list`, `users_in_groups`,  `users` 
                                            WHERE groups_list.group_id = users_in_groups.group_id AND 
                                            users.user_id = users_in_groups.user_id AND is_group_leader = 'yes' AND users.user_id = '$user_id'";

                                            $resultstudent = mysqli_query( $connection, $studentdatasql );

                                            if($resultstudent){
                                                echo "<div>";
                                                while ( $row2 = mysqli_fetch_assoc( $resultstudent ) ) {
                                                    echo " <h5>This student is a leader of " . $row2['group_name'] . "</h5>";
                                                }
                                                echo "</div>";
                                                echo "<hr>";
                                            }
									}
								} else {
									if(!$is_production){
										echo $sql;
									}
									echo "No Groups created yet";
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

                            <h2 class="title"><strong>Honor Groups</strong></h2>
                            <p class="lead">View Group Leaders + Profiles</p>
                        </div>	
						</div>
						<div class="" style="padding-left:15px; padding-right:15px">

							<div class="article-container">
								<hr>
								<?php
                                initial_load($connection);

								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



 
