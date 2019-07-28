<?php include "../../templates/user_header.php";

function initial_load($connection, $user_id){
	$sql = "SELECT groups_list.*, users_in_groups.* FROM `groups_list`, `users_in_groups` WHERE users_in_groups.user_id = '$user_id' AND users_in_groups.group_id = groups_list.group_id AND users_in_groups.is_group_leader = 'yes'";
                                $result = mysqli_query( $connection, $sql );
                                if($result){                                    
									while ( $row = mysqli_fetch_assoc( $result ) ) {
										echo "
                                    <div>
                                        <h3>" . $row[ 'group_name' ] . "</h3>
                                        <p>" . $row[ 'group_description' ] . "</p>

                                        <form method='POST'>
                                        <button name = 'group' type=submit class = 'btn btn-success' value=". $row[ 'group_id' ] .">Select Group</button>
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

if(isset($_POST['group'])){
    $group_id = mysqli_real_escape_string($connection, $_POST['group']);
    // Create a session based on what group
    $sql = "SELECT * FROM `users_in_groups` WHERE user_id = '$user_id' AND group_id = $group_id";
    $result = mysqli_query($connection, $sql);

    if($result){
        $_SESSION['group_leader'] = $group_id;
        Redirect('hub');
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

                                <h1 class="display-3">Welcome Group Leader</h1>
                                <p class="lead">Choose a group below to manage the logistics of.</p>
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
                            <div class="article-container">

								<?php
                                if(!isset($_POST['search_user'])){
                                    initial_load($connection, $user_id);
                                } 
								?>
							</div>
                            </div>
                            <br>
                            </div>
                        </div>
                    </div>
      			</div>

            </div>
        </div>

