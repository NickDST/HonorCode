<?php
session_start();
require_once('../../assets/includes/dbh.inc.php');
require_once('../../assets/includes/email_functions.php');


if (!isset($_SESSION['user'])) {
	Redirect('../login/login');
  exit;
}

$user_id = $_SESSION['user'];


// $sql = "SELECT * FROM students WHERE studentid = '$id'";
// //echo $sql;
// $result = mysqli_query($connection, $sql);
// while ($student = $result->fetch_assoc()): 

// $studentname = $student['name'];

// endwhile;
//include 'hubheader.php'


?>

<!DOCTYPE html>
<html>
 <head>
  <title>Availability Times </title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <link rel="stylesheet" href="calendar.css"> 
	 
	    <!-- Bootstrap core CSS     -->
<!--   <link href="assets/css/bootstrap.min.css" rel="stylesheet" /> -->
	 
	 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  <script>
	

  $(document).ready(function() {
   var calendar = $('#calendar').fullCalendar({
    editable:true,
    header:{
     left:'prev,next today',
     center:'title',
     right:'month,agendaWeek,agendaDay'
    },
    events: 'load.php',
    //select a particular cell, dragging events and stuff
    selectable:true,
    selectHelper:true,
    select: function(start, end, allDay)
    {
     var title = 'Available';
     if(title)
     {
      var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
      var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
      $.ajax({
       url:"insert.php",
       type:"POST",
       data:{title:title, start:start, end:end},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Added Successfully");
       }
      })
     }
    },
    //You are allowed to edi the table....
    editable:true,
    eventResize:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function(){
       calendar.fullCalendar('refetchEvents');
       //alert('Event Update');
      }
     })
    },

    eventDrop:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function()
      {
       calendar.fullCalendar('refetchEvents');
       //alert("Event Updated");
      }
     });
    },

    eventClick:function(event)
    {
     if(confirm("Are you sure you want to remove this Availability Time?"))
     {
      var id = event.id;
      $.ajax({
       url:"delete.php",
       type:"POST",
       data:{id:id},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Event Removed");
       }
      })
     }
    },

   });
  });

  </script>
 </head>
 <body>
  <br />
<!--	
  <h2 align="center"><a href="hub.php"><?php
//	echo $studentname;
	?></a></h2>
  <br />
-->
		 
 <div class="content">
            <div class="container-fluid">
                <div class="row">
					
                    <div class="col-md-8">
                        <div class="card">

                            <div class="header" style = "margin-left:20px; margin-top:10px;">
                                <h2 class="title"></h2>
                                <h4 class="category">Set Availability</h4>
                            </div>
                       <div class = "" style= "padding-left:15px;">
  						<div class="container">
   						<div id="calendar"></div>
	  
  						</div>
						</div> 
					</div>
                </div>
					
					 <div class="col-md-4">
                        <div class="card">

                            <div class="header" style = "margin-left:20px; margin-top:10px;">
                                <h2 class="title">Notes</h2>
                                <p class="category">Click a cell to set a time where you are available</p>
                            </div>
                       <div class = "" style= "padding-left:15px; padding-bottom:20px;">
							<!--  Insert other things here				-->
							
							<h4>Make sure to set 2 availability dates for each month. /(' u ')/ <br>-VP</h4>
						
				<p>This is because tutor requestors cannot choose times within three days of today. (to allow tutors to know a certain time in advance) If you have any questions, contact the officers.</p>
						   
						   <p>Click "week" to drag the box to the specific time you want to be set to available.</p>
						   
						   <p>Those who look for requests under your subject fields will see these times.</p>
						   
						   <p>Click on the event time to delete it.</p>
						   
						    
						   <p>Subjects I can tutor:</p>
                           <?php
                                    $sql = "SELECT users_in_subjects.*, tutor_subjects.* FROM users_in_subjects, tutor_subjects WHERE users_in_subjects.subject_id = tutor_subjects.subject_id AND users_in_subjects.user_id = '$user_id'";
                                    $result = mysqli_query($connection, $sql);
                                    if(mysqli_num_rows($result) > 0){
                                        while ( $row = mysqli_fetch_assoc( $result ) ) {
                                            echo $row['subject_name'];
                                            echo "<br>";
                                        }
                                    } else {
                                        echo "No Subjects Currently set to Tutor";
                                    }

                                ?>
				<br>		   	   
				<a href="hub" class ="btn btn-info">Back to Hub</a>
				<br>
				<br>
				
				

						   
	  
  						</div>
						</div> 
					</div>
                </div>
          </div>
		</div>
   </div>
				
 </body>
</html>
