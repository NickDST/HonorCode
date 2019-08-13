<?php
include "../../assets/includes/dbhcal.inc.php";
session_start();


if (isset($_SESSION['subject_id'])) {
	$subject_id = $_SESSION['subject_id'];
	
} else {
	//echo "nothing yet";
}

$data = array();

//$query = "SELECT available_times.*, students_in_subjects.* FROM available_times, students_in_subjects WHERE available_times.studentid = students_in_subjects.studentid AND students_in_subjects.subject = '$subjectname' AND available_times.hold = 'free' AND datetime_start > NOW()";

//$query = "SELECT available_times.*, students_in_subjects.* FROM available_times, students_in_subjects WHERE available_times.studentid = students_in_subjects.studentid AND students_in_subjects.subject = '$subjectname' AND available_times.hold = 'free' AND datetime_start > adddate(now(),+2)";

$query = "SELECT available_times.*, users_in_subjects.*, users.* FROM available_times, users_in_subjects, users WHERE available_times.user_id = users_in_subjects.user_id AND users_in_subjects.subject_id = '$subject_id' AND users.user_id = available_times.user_id AND available_times.hold = 'free'";


$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["id"],
	 //taking out the titles
  'title'   => $row["user_full_name"],
  'start'   => $row["datetime_start"],
  'end'   => $row["datetime_end"]
 );
}

echo json_encode($data);
