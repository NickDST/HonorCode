<?php  
session_start();
include "../../assets/includes/dbh.inc.php";
include "../../assets/includes/login_functions.php";
//echo $include_test;
//Redirecting the user if they are already logged in. 
if ( isset( $_SESSION[ 'user' ] ) ) {
  Redirect('../hub/hub');
}

//Loading the code on an HTTP Post event
if ( isset( $_POST ) & !empty( $_POST ) ) {
  //sanitizing the data inputted with mysqli_real_escape_string
	$username = mysqli_real_escape_string( $connection, $_POST[ 'username' ] );
	$password = mysqli_real_escape_string( $connection, $_POST[ 'password' ] );

  //a function created in common_functions.php, it returns True or False if the person can log in
	if(valid_user_login($username, $password, $connection)){
    $logged_in = find_login_user_id($username, $connection);
    $_SESSION[ 'user' ] = $logged_in;

    Redirect('../hub/hub');
  } else {
    Redirect('login?error=Incorrect Username or Password');
  }
	
} ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="description" content="">
  <meta name="author" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" media="screen" href="login_assets/css/style.css">

</head>
<body>
<div id="particles-js">
  <div id="login">
    <h3>Users Login Page</h3>
    <br>
        <form method="POST">
          <div>
            <label for="username">Username</label>
            <br>
            <input type="username" id="email" name="username">
          </div>
          <div>
            <label for="password">Password</label>
            <br>
           <input type="password" id="password" name="password">
          </div>
          <br>
          <input type="submit" class = "btn" value="Login">
        </form>
      </div>
</div>

<!-- scripts -->
<script src="login_assets/particles.js"></script>
<script src="login_assets/js/app.js"></script>

<!-- Credit thi guy for the sick login -->
<!-- MIT License https://github.com/VincentGarreau/particles.js/  -->
</body>
</html>
