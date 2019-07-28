<?php include "../../templates/user_header.php";

//TODO: This is to view all the available groups, select a group, and apply for group membership

//Show Involved Groups/Maybe news for some groups
//Shows pending groups
//Show Search results for groups

//Extension Pages : group_details.php,

$is_production = false;

function initial_load($connection){
	$sql = "SELECT * FROM `groups` LIMIT 20";
                                $result = mysqli_query( $connection, $sql );
                                if($result){
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

                            <h2 class="title"><strong>Honor Groups</strong></h2>
                            <p class="lead">View Groups and their details.</p>
							<h3><a class = 'btn btn-warning' href="pending_requests">View Pending Requests</a></h3>
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
                                    initial_load($connection);
                                } else {
                                    $search = mysqli_real_escape_string($connection, $_POST['search_user']);
                                    search_load($connection, $search);
                                }
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



 
