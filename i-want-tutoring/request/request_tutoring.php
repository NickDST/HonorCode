<?php
session_start();
require_once('../../assets/includes/dbh.inc.php');
require_once('../../assets/includes/email_functions.php');

$phase1 = True;
$phase2 = False;
$phase2a = True;
$phase2b = False;
$phase3 = False;


if(isset($_GET['setsubject'])){
	$subject_id = mysqli_real_escape_string($connection, $_GET['choosesubject']);
	$_SESSION[ 'subject_id' ] = $subject_id;
	$phase1 = False;
	$phase2 = True;
} else {
	unset( $_SESSION[ "subject_id" ] );
	unset( $_SESSION[ "tutortime" ] );
}

if ( isset( $_SESSION[ 'tutortime' ] ) ) {
	$tutor_id = $_SESSION[ 'tutortime' ];

	$sql = "SELECT * FROM `available_times` WHERE id = '$tutor_id'";
	$result = mysqli_query($connection, $sql);
	if($result){
		while ( $row = mysqli_fetch_assoc( $result ) ) {
			$datetime_start = $row['datetime_start'];
			$datetime_end = $row['datetime_end'];
			$user_full_name = $row['user_full_name'];
			$tutor_id = $row['user_id'];
		}
	}

	$phase2a = False;
	$phase2b = True;

}


if(isset($_POST['send_request'])){
	//update SQL tables and send 2 emails
	echo "Sent emails to people";
	$requestor_name = mysqli_real_escape_string($connection, $_POST['requestor_name']);
	$requestor_email = mysqli_real_escape_string($connection, $_POST['requestor_email']);


	$phase2 = False;
	$phase2a = False;
	$phase2b = False;
	$phase3 = True;

}


?>
<br />

<?php if($phase1){?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
			<div class="col-md-12">
				<div class="card">

				<div class="header" style="margin-left:20px; margin-top:10px; margin-right:20px">
							<h4>Thank you for Using HonorCode! /(' u ')/ </h4>

							<br>
							<strong><p> Please select a subject you want to be tutored in! </p></strong>
							<br>
							<form method="GET">
							<select name="choosesubject" id="">
							<?php 
							$sql = "SELECT * FROM `tutor_subjects`";
							$result = mysqli_query($connection, $sql);
							if($result){
								while ( $row = mysqli_fetch_assoc( $result ) ) {
									$subject_name = $row['subject_name'];
									$subject_id = $row['subject_id'];
									echo "<option value='$subject_id'>$subject_name</option>";
								}
							}
							?>
							</select>
							<br>
							<br>
							<button class="btn btn-success" name='setsubject'>Choose Subject</button>
							</form>
							<br>
							<hr>
							<a href="../../index" class="btn btn-info">Back to Main Page</a>
							<br>
							<br>
                        </div>
                    </div>


				</div>
   			 </div>
		</div>
    </div>
</div>
<?php }?>

<?php if($phase2){?>
<div class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-8">
                <div class="card">

                    <div class="header" style="margin-left:20px; margin-top:10px;">
                        <h2 class="title"></h2>
                        <h4 class="category">Click on a Tutoring Opportunity</h4>
                    </div>
                    <div class="" style="padding-left:15px;">
                        <div class="container">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="header" style="margin-left:20px; margin-top:10px;">
                        <h2 class="title">Instructions</h2>
                        <!-- <p class="category">Click a <strong>blue date </strong> to set a time where you are available</p> -->
                </div>
                    <div class="" style="padding-left:15px; padding-bottom:20px; padding-right:15px">
                        <!--  Insert other things here				-->

						<? echo "Loaded Tutoring Opportunities for: " . $_SESSION[ "subject_id" ] ; ?>

						<?php if($phase2a){?>

						<br>
						<br>

                        <h4>Click on a <strong> Blue Box </strong>to request that time!</h4>
						<br>
                        <p>Please note that if you select a time within 48 hours to now, our tutors <strong> may turn down the tutor request.</strong>
                        </p>

						<?php }?>

						<?php if($phase2b){?>
						<br>
						<br>
						<?php 
						echo "Datetime Start: " . $datetime_start; 
						echo "<br>";
						echo "Datetime End: " . $datetime_end; 
						echo "<br>";
						echo "Tutor: " . $user_full_name; 
						echo "<br>";
						
						?>
						<br>
						<form method="POST">

						<input name='requestor_name' type="text" class="form-control" placeholder="Your full name">
						<br>
						<input name='requestor_email' type="email" class="form-control" placeholder="Your email">
						<br>
						<button class="btn btn-success" name = "send_request">Submit the Tutor Request!</button>
						
						</form>
						<?php }?>
						<hr>
						<br>
                        <a href="../../index" class="btn btn-info">Back to Main Page</a>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php }?>

<?php if($phase3){?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
			<div class="col-md-12">
				<div class="card">

				<div class="header" style="margin-left:20px; margin-top:10px;">
							
							<h2>Thanks again for using HonorCode! You will receive an email once our Tutor has accepted your request!</h2>
							<br>
							<a href="../../index" class="btn btn-info">Back to Main Page</a>
							<br>
							<br>
                        </div>
                    </div>

				</div>
   			 </div>
		</div>
    </div>
</div>
<?php }?>

</body>

</html>

<!--This is the javascript for the calendar-->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script>
$(document).ready(function() {
    var calendar = $('#calendar').fullCalendar({
        editable: false,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek'
        },
        events: 'tutorload.php',
        //select a particular cell, dragging events and stuff
        //loads the things from the database
        selectable: false,
        selectHelper: false,
        select: function(start, end, allDay) {
            var title = prompt("Enter Event Title");
            if (title) {
                var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                $.ajax({
                    url: "insert.php",
                    //This inserts
                    type: "POST",
                    data: {
                        title: title,
                        start: start,
                        end: end
                    },
                    success: function() {
                        calendar.fullCalendar('refetchEvents');
                        alert("Added Successfully");
                    }
                })
            }
        },
        //You are allowed to edit the table....
        editable: false,
        eventResize: function(event) {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            var title = event.title;
            var id = event.id;
            $.ajax({
                url: "update.php",
                type: "POST",
                data: {
                    title: title,
                    start: start,
                    end: end,
                    id: id
                },
                success: function() {
                    calendar.fullCalendar('refetchEvents');
                    alert('Event Update');
                }
            })
        },

        eventDrop: function(event) {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            var title = event.title;
            var id = event.id;
            $.ajax({
                url: "update.php",
                type: "POST",
                data: {
                    title: title,
                    start: start,
                    end: end,
                    id: id
                },
                success: function() {
                    calendar.fullCalendar('refetchEvents');
                    alert("Event Updated");
                }
            });
        },

        eventClick: function(event) {
            if (confirm("Schedule a tutor for this date?")) {
                var id = event.id;
                var title = event.title;
                var studentid = event.studentid;
                $.ajax({
                    url: "dateidtophp.php",
                    type: "POST",
                    data: {
                        id: id,
                        title: title,
                        studentid: studentid
                    },
                    success: function() {
                        calendar.fullCalendar('refetchEvents');
                        //        alert("Parameters Set");
                        document.location.reload(true)
                    }
                })
            }
        },
    });
});
</script>



</body>

</html>