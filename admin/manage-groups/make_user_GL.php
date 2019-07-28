<?php
include "../../templates/admin_header.php";
include "../../templates/admin_group_edit.php";

$userid = mysqli_real_escape_string($connection, $_GET['userid']);
$groupid = mysqli_real_escape_string($connection, $_GET['groupid']);

$sql = "SELECT * FROM users_in_groups WHERE user_id = '$userid' AND group_id = '$groupid'";
$result = mysqli_query($connection, $sql);
$queryResults = mysqli_num_rows( $result );

if($queryResults == 0){
    $sql = "INSERT INTO users_in_groups (user_id, group_id, is_group_leader)
    VALUES ('$userid', '$groupid', 'yes');";
    $result = mysqli_query($connection, $sql);
    if($result){
        Redirect("manage_groups?success=Successful Knighting");
    } else {
        Redirect("manage_groups?error=Something Went Wrong");
    }

} else {
    $sql = "UPDATE users_in_groups
    SET is_group_leader = 'yes'
    WHERE user_id = '$userid' AND group_id = '$groupid';";
    $result = mysqli_query($connection, $sql);
    if($result){
        Redirect("manage_groups?success=Successful Knighting");

    } else {
        Redirect("manage_groups?error=Something Went Wrong");
    }
}

if(!isset($_GET['userid']) || !isset($_GET['groupid']) ){
    Redirect("manage_groups?error=Something Went Wrong");
}



?>