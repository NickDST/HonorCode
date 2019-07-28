<?php include "../../templates/admin_header.php";

//enabling echo SQL for debugging and disabling redirects
$is_production = false;

//When the person submits the form the data is escaped and validated
if ( isset( $_POST ) & !empty( $_POST ) ) {
	$group_name = mysqli_real_escape_string( $connection, $_POST[ "group_name" ] );
	$teacher_contact = mysqli_real_escape_string( $connection, $_POST[ "teacher_contact" ] );
	$group_description = mysqli_real_escape_string( $connection, $_POST[ "group_description" ] );
	$group_type = mysqli_real_escape_string( $connection, $_POST[ "group_type" ] );
    $tutoring_services = mysqli_real_escape_string( $connection, $_POST[ "tutoring_services" ] );

	//in assets/includes/admin_functions
    if(!(check_if_groupname_exists($group_name, $connection))){
		
		//Inserting
            $sql = "INSERT INTO `groups_list` (group_name, advisor_contact, group_description, group_type, tutoring_services) 
            VALUES ('$group_name', '$teacher_contact', '$group_description', '$group_type' , '$tutoring_services');";
            $result = mysqli_query( $connection, $sql );
            
            if ( $result ) {
				$group_id = find_groupid_from_name($group_name, $connection);
				if(!$is_production){
					echo $group_id;
				} else {
					Redirect("add_group_leaders?groupid=" . $group_id);
				}
                

            } else {

				if(!$is_production){
					echo $sql;
				} else {
					Redirect("add_group?error = Something went wrong! Please make sure to fill all the fields");
				}

            }
        } else {
            Redirect("add_group?error = Group name has already been taken");
        }
}
?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">

				<div class="col-md-12">
					<div class="card">

						<div class="header jumbotron">
							<h2 class="title">Create a new group</h2>
						</div>
						<div class="" style="padding-left:15px;">
							<div class="movedown">
								<div class="student-specify">
									<div class="">
										<form class="" method="POST">
											<br>
											<label for="">Name of this Group</label>
											<br>
											<input type="text" name="group_name" class="form-control" placeholder="Name of Group" required maxlength=100>
											<br>
											<label for="">Email of Responsible Adult</label>
											<br>
											<input type="email" name="teacher_contact" id="" class="form-control" placeholder="" required maxlength=100>
											<br>
											<label for="">Group Description + Requirements</label>
											<div class="form-group">
												<textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="group_description" placeholder="A Detailed Description" required maxlength=500 style = "resize:none;" ></textarea>
											</div>

											<label for="">Group Type</label>
                                            <br>
                                            <select name="group_type" id="" class="custom-select">
                                                <option value="">Please choose one of the following:</option>
                                                <option value="Honor Society">Honor Society</option>
                                                <option value="School Club">School Club</option>
                                            </select>
                                            <br>
                                            <br>
                                            <label for="">Can this Group Offer Tutoring Services?</label>
                                            <br>
                                            <select name="tutoring_services" id="" class="custom-select">
                                                <option select>Please choose one of the following:</option>
                                                <option value="yes">Yes.</option>
                                                <option value="no">No.</option>
                                            </select>
											<br>
											
											
											
											<br>


											<button class="btn btn-success" type="submit">submit</button>

										</form>
										<br>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>