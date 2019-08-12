<?php include "../../templates/user_header.php";


$is_production = false;

function initial_load($connection, $user_id){
	$sql = "SELECT * FROM `projects` WHERE type = 'public' OR (type = 'private' AND initiated_by = '$user_id') LIMIT 20";
                                $result = mysqli_query( $connection, $sql );
                                if($result){
									while ( $row = mysqli_fetch_assoc( $result ) ) {
										echo "
                                    <div>
                                        <h3>" . $row[ 'project_name' ] . "</h3>
                                        <p>" . substr($row[ 'project_details' ], 0, 200) . "...</p>
                                        <a class = 'btn btn-light' href = 'add_service_specify?projectid=".$row['project_id']."'>Add Service</a>
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


function search_load($connection, $search, $user_id){
    $sql = "SELECT * FROM `projects` WHERE (type = 'public' AND
             (group_name LIKE '%$search%' OR advisor_contact LIKE '%$search%' OR group_type LIKE '%$search%' OR group_description)) LIMIT 30";
			
	$result = mysqli_query( $connection, $sql );
	$queryResults = mysqli_num_rows( $result );
	if ( $queryResults > 0 ) {
		while ( $row = mysqli_fetch_assoc( $result ) ) {
			echo "
        <div>
            <h3>" . $row[ 'project_name' ] . "</h3>
            <p>" . substr($row[ 'project_details' ], 0, 200) . "...</p>
            <a class = 'btn btn-secondary' href = 'add_service_specify?projectid=".$row['project_id']."'>Add Service</a>
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

                            <h2 class="title"><strong>Add and Create Projects</strong></h2>
                            <p class="lead">Add yourself to projects and create new projects</p>
							<h3><a class = 'btn btn-warning' href="add_project">Create a new Project</a></h3>
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
                                    initial_load($connection, $user_id);
                                } else {
                                    $search = mysqli_real_escape_string($connection, $_POST['search_user']);
                                    search_load($connection, $search, $user_id);
                                }
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>