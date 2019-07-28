<?php

//Testing whether or not the login credentials are valid
function valid_admin_login($post_username, $post_password, $connection){
    $username = mysqli_real_escape_string($connection, $post_username);
    $password = mysqli_real_escape_string($connection, $post_password);
	$password1 = md5($password);
	$md5_hash_password = sha1($password1);
    //logging the user in
	$sql = "SELECT username FROM `admin_login` WHERE username = '$username' AND password = '$md5_hash_password';";
	$result = mysqli_query( $connection, $sql );
	$count = mysqli_num_rows( $result );
	if ( $count == 1 ) {
		return true;
	} else {
		return false;
	}
}


//Testing whether or not the login credentials are valid
function valid_user_login($post_username, $post_password, $connection){
    $username = mysqli_real_escape_string($connection, $post_username);
    $password = mysqli_real_escape_string($connection, $post_password);
	$password1 = md5($password);
	$md5_hash_password = sha1($password1);
    //logging the user in
	$sql = "SELECT username FROM `users` WHERE username = '$username' AND password = '$md5_hash_password';";
	$result = mysqli_query( $connection, $sql );
	$count = mysqli_num_rows( $result );
	if ( $count == 1 ) {
		return true;
	} else {
		return false;
	}
}


function find_login_user_id($username, $connection){
    $sql = "SELECT user_id FROM `users` WHERE username = '$username'";
	$result = mysqli_query( $connection, $sql );
	$count = mysqli_num_rows( $result );
	if ( $count == 1 ) {
		while ( $user_name = $result->fetch_assoc() ):
		    $login = $user_name['user_id'];
        endwhile;
        return $login;
    }
}



//Testing whether or not the login credentials are valid
function valid_GL_login($post_username, $post_password, $connection){
    $username = mysqli_real_escape_string($connection, $post_username);
    $password = mysqli_real_escape_string($connection, $post_password);
	$password1 = md5($password);
	$md5_hash_password = sha1($password1);
    //logging the user in
	$sql = "SELECT username FROM `GL_login` WHERE username = '$username' AND password = '$md5_hash_password';";
	$result = mysqli_query( $connection, $sql );
	$count = mysqli_num_rows( $result );
	if ( $count == 1 ) {
		return true;
	} else {
		return false;
	}
}


function find_login_admin_id($username, $connection){
    $sql = "SELECT admin_login_id FROM `admin_login` WHERE username = '$username'";
	$result = mysqli_query( $connection, $sql );
	$count = mysqli_num_rows( $result );
	if ( $count == 1 ) {
		while ( $user_name = $result->fetch_assoc() ):
		    $login = $user_name['admin_login_id'];
        endwhile;
        return $login;
    }
}


function check_admin_status($username, $connection){
	$sql = "SELECT admin FROM `equipt_users` WHERE username = '$username' AND admin = '1';";
	$result = mysqli_query( $connection, $sql );
	$count = mysqli_num_rows( $result );
	if ( $count == 1 ) {
		return true;
	} else {
		return false;
	}
}


function check_smsleader_status($username, $connection){
	$sql = "SELECT sms_leaders FROM `equipt_users` WHERE username = '$username' AND sms_leaders = '1';";
	$result = mysqli_query( $connection, $sql );
	$count = mysqli_num_rows( $result );
	if ( $count == 1 ) {
		return true;
	} else {
		return false;
	}
}


function check_teacher_status($username, $connection){
	$sql = "SELECT teachers FROM `equipt_users` WHERE username = '$username' AND teachers = '1';";
	$result = mysqli_query( $connection, $sql );
	$count = mysqli_num_rows( $result );
	if ( $count == 1 ) {
		return true;
	} else {
		return false;
	}
}


function register_user(){
    //todo: create this function
}

function validate_register_user(){
    //todo: create this function
}

function get_username_from_id($login_id, $connection){
    $sql = "SELECT username from equipt_users WHERE login_id = '$login_id'";
    $result = mysqli_query($connection, $sql);
    while ( $row = mysqli_fetch_assoc( $result ) ) {
        $username = $row['username'];
    }
    return $username;
}


function record_ips(){
    //todo: create this function
}





?>