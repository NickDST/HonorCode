<?php
/*
include "includes/dbh.inc.php";

$sql1 = "SELECT * FROM students_in_subjects WHERE subject = 'biology' ";
$result = mysqli_query($connection, $sql);
while ($student = $result->fetch_assoc()): ?>	
*/
include "../assets/includes/dbhcal.inc.php";
session_start();


if (isset($_SESSION['subjectname'])) {
	$subjectname = $_SESSION['subjectname'];
	
	?> 	 <?php	 
} else {
	//echo "nothing yet";
}

$data = array();

//$query = "SELECT available_times.*, students_in_subjects.* FROM available_times, students_in_subjects WHERE available_times.studentid = students_in_subjects.studentid AND students_in_subjects.subject = '$subjectname' AND available_times.hold = 'free' AND datetime_start > NOW()";

//$query = "SELECT available_times.*, students_in_subjects.* FROM available_times, students_in_subjects WHERE available_times.studentid = students_in_subjects.studentid AND students_in_subjects.subject = '$subjectname' AND available_times.hold = 'free' AND datetime_start > adddate(now(),+2)";

$query = "SELECT available_times.*, users_in_subjects.*, users.* FROM available_times, users_in_subjects, users WHERE available_times.user_id = students_in_subjects.user_id AND users_in_subjects.subject_name = '$subjectname' AND users.user_id = available_times.user_id AND available_times.hold = 'free'";


$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["id"],
	 //taking out the titles
  'title'   => $row["name"],
  'start'   => $row["datetime_start"],
  'end'   => $row["datetime_end"]
 );
}

echo json_encode($data);
