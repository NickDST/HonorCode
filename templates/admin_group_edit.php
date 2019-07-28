<?php
$group_id = mysqli_real_escape_string($connection, $_GET['groupid']);
$sql = "SELECT * FROM `groups` WHERE group_id = '$group_id'";
    $result = mysqli_query( $connection, $sql );
    if($result){
            while ( $row = $result->fetch_assoc() ):
                $group_name = $row['group_name'];
                $group_description = $row['group_description'];
                $advisor_contact = $row['advisor_contact'];
                $group_type = $row['group_type'];
                $tutoring_services = $row['tutoring_services'];
                $date_added = $row['date_added'];
            endwhile;
} 

if(mysqli_num_rows( $result ) != 1){
    Redirect("manage_groups?error=Group Does Not Exist");
}


?>