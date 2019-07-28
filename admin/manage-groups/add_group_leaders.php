<?php 
include "../../templates/admin_header.php";
include "../../templates/admin_group_edit.php";

function initial_load($connection, $group_id){
    $sql = "SELECT * FROM users ORDER BY username ASC";
	$result = mysqli_query( $connection, $sql );
	$queryResults = mysqli_num_rows( $result );
	if ( $queryResults > 0 ) {
		while ( $row = mysqli_fetch_assoc( $result ) ) {
			echo "
			<div>
            <h3>" . $row[ 'first_name' ] . " " . $row['last_name'] . "</h3>
            <p>" . $row[ 'username' ] . "</p>
            <p>" . $row[ 'email' ] . "</p>
            <a class = 'btn btn-warning' href = 'view_user?userid=".$row['user_id'] . "'&groupid='".$group_id."'> View Profile </a>
            <a class = 'btn btn-primary' href = 'make_user_GL?userid=".$row['user_id']  . "&groupid=$group_id'> Make User group Leader</a>
            </div>
            <hr>";
		}
	}
}

function search_load($connection, $group_id, $search){
    $sql = "SELECT * FROM users WHERE 
             (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR user_id LIKE '%$search%' OR email LIKE '%$search%' OR date_created LIKE '%$search%')";
			
	$result = mysqli_query( $connection, $sql );
	$queryResults = mysqli_num_rows( $result );
	if ( $queryResults > 0 ) {
		while ( $row = mysqli_fetch_assoc( $result ) ) {
			echo "
			<div>
            <h3>" . $row[ 'first_name' ] . " " . $row['last_name'] . "</h3>
            <p>" . $row[ 'username' ] . "</p>
            <p>" . $row[ 'email' ] . "</p>
            <a class = 'btn btn-warning' href = 'view_user?userid=".$row['user_id'] . "'&groupid='".$group_id."'> View Profile </a>
            <a class = 'btn btn-primary' href = 'make_user_GL?userid=".$row['user_id']  . "&groupid='".$group_id."'> Make User group Leader</a>
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
                            <br>
                            <div class = "" style= "padding-left:15px; padding-right:15px;">

                                <h3 class="display-3">Give a User Group Leader Privileges</h3>
                                <p class="lead"></p>
                                <hr class="my-2">

                                <br>
                            </div>
                        </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <br>
                            <div class = "" style= "padding-left:15px; padding-right:15px;">
                            <form method="POST">
                                <input class = 'form-control' type="text" name="search_user" placeholder="Search for a Specific User" maxlength=100>
                                <br>
								<button type="submit" name="submit-search" class = 'btn btn-success'>Submit</button>
							</form>
							<div class="article-container">
							<hr>
                                <?php
                                if(!isset($_POST['search_user'])){
                                    initial_load($connection, $group_id);
                                } else {
                                    $search = mysqli_real_escape_string($connection, $_POST['search_user']);
                                    search_load($connection, $group_id, $search);
                                }
								
								?>
                            </div>
                            <br>
                            </div>
                        </div>
                    </div>
      			</div>

            </div>
        </div>


 
