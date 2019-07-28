<?php

function find_groupid_from_name($group_name, $connection){
    $sql = "SELECT group_id FROM `groups` WHERE group_name = '$group_name'";
    $result = mysqli_query( $connection, $sql );
    if($result){
        $count = mysqli_num_rows( $result );
        if ( $count == 1 ) {
            while ( $row = $result->fetch_assoc() ):
                $group_id = $row['group_id'];
            endwhile;
            return $group_id;
        }
    } 
}

function check_if_groupname_exists($group_name, $connection){
    $sql = "SELECT group_id FROM `groups` WHERE group_name = '$group_name'";
    $result = mysqli_query( $connection, $sql );
    if($result){
        $count = mysqli_num_rows( $result );
        if($count > 0){
            return true;
        } else {
           return false;
        }
    } return false;
}


function create_update_form_array($update_form_values){
    $update_array = array();
    for($i = 0; $i < count($update_form_values); $i++){
        if(!empty($_POST[$update_form_values[$i]])){
            $temp_array = array($update_form_values[$i] => $_POST[$update_form_values[$i]]);
            $update_array = array_merge($update_array, $temp_array);
        }
    }
    return $update_array;
}


function update_if_exists($connection, $array, $table, $unique_id, $unique_id_value){
    foreach($array as $x => $value) {
        $escaped_value = mysqli_real_escape_string($connection, $value);
        $sql =  "UPDATE `$table` SET `$x` = '$escaped_value' WHERE $unique_id = '$unique_id_value'"; 
        $result = mysqli_query($connection, $sql);
    }
}





?>