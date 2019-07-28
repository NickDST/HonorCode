<?php 
include "../../templates/admin_header.php";
include "../../templates/admin_group_edit.php";

if(isset($_GET['userid']) && isset($_GET['groupid'])){

    $userid = mysqli_real_escape_string($connection, $_GET['userid']);
    $groupid = mysqli_real_escape_string($connection, $_GET['groupid']);

    $sql = "UPDATE `users_in_groups`
    SET is_group_leader = 'no'
    WHERE user_id = '$userid' AND group_id = '$groupid';";
    $result = mysqli_query($connection, $sql);
    if($result){
        Redirect("edit_group?success=Successful Revoking&groupid=$groupid");

    } else {
        Redirect("manage_groups?error=Something Went Wrong");
    }
}

?>