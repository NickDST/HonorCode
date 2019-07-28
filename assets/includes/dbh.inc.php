<?php
$connection = mysqli_connect("localhost", 'root', 'password');
if (!$connection) {
 die("Database Connection Failed". mysqli_error($connection));
}
$select_db = mysqli_select_db($connection, 'tutors_july_2019');
if (!$select_db) {
 die("Database Selection Failed". mysqli_error($connection));
}

function Redirect($url){
    echo "<script>window.location.href = '$url';</script>";
}



?>