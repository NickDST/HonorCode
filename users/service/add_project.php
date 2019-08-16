<?php include "../../templates/user_header.php";

//enabling echo SQL for debugging and disabling redirects
$is_production = false;

//When the person submits the form the data is escaped and validated
if ( isset( $_POST ) & !empty( $_POST ) ) {
	$project_name = mysqli_real_escape_string( $connection, $_POST[ "project_name" ] );
	$requestor_email = mysqli_real_escape_string( $connection, $_POST[ "requestor_email" ] );
	$project_details = mysqli_real_escape_string( $connection, $_POST[ "project_details" ] );
	$datetime_start = mysqli_real_escape_string( $connection, $_POST[ "datetime_start" ] );
    $type = mysqli_real_escape_string( $connection, $_POST[ "type" ] );
    $tutoring_event = mysqli_real_escape_string( $connection, $_POST[ "tutoring_services" ] );



		
		//Inserting
            $sql = "INSERT INTO `projects` (project_name, requestor_email, project_details, datetime_start, type, tutoring_event, initiated_by) 
            VALUES ('$project_name', '$requestor_email', '$project_details', '$datetime_start' , '$type', '$tutoring_event', '$user_id');";
            $result = mysqli_query( $connection, $sql );
            
            if ( $result ) {
                Redirect("add_service?success=Project Successfully Entered. Make sure to navigate to the project and ADD your service hours.");
                

            } else {

				if(!$is_production){
					echo $sql;
				} else {
					Redirect("add_project?error=Something went wrong! Please make sure to fill all the fields");
				}

            }
}
?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">

				<div class="col-md-12">
					<div class="card">

						<div class="header jumbotron">
							<h2 class="title">Create a New Project</h2>
						</div>
						<div class="" style="padding-left:15px;">
							<div class="movedown">
								<div class="student-specify">
									<div class="">
										<form class="" method="POST">
											<br>
											<label for="">Name of this Project</label>
											<br>
											<input type="text" name="project_name" class="form-control" placeholder="Name of Group" required maxlength=100>
											<br>
											<label for="">Email of Requestor</label>
											<br>
											<input type="email" name="requestor_email" id="" class="form-control" placeholder="" required maxlength=100>
											<br>
											<label for="">Detailed Project Description</label>
											<div class="form-group">
												<textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="project_details" placeholder="A Detailed Description" required maxlength=500 style = "resize:none;" ></textarea>
											</div>

                                            <label for="">Date of Project (Please make sure you are using Chrome)</label>
											<div class="form-group">
                                            <input type="date" name="datetime_start" id="" class="form-control" placeholder="" required maxlength=100>

											</div>

											<label for="">Private or Public Project</label>
                                            <br>
                                            <select name="type" id="" class="custom-select">
                                                <option value="public">Please choose one of the following:</option>
                                                <option value="public">Public, anyone can see this project. </option>
                                                <option value="private">Private, only I will be able to view this project.</option>
                                            </select>
                                            <br>
                                            <br>
                                            <label for="">Is this a tutoring event?</label>
                                            <br>
                                            <select name="tutoring_services" id="" class="custom-select">
                                                <option value='no'>Please choose one of the following:</option>
                                                <option value="yes">Yes.</option>
                                                <option value="no">No.</option>
                                            </select>
											<br>
											<br>
											<p>After submitting the service project, please find this projects under the project list and add your service hours to this project.</p>
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