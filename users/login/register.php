<?php
session_start();
include "../../assets/includes/dbh.inc.php";
include "../../assets/includes/login_functions.php";
include "../../assets/includes/email_functions.php";


// if ( isset( $_SESSION[ 'user' ] ) ) {
//     Redirect('../hub/hub');
// }

//tutorsEmail($recipients, $subject, $content)

if ( isset( $_POST ) & !empty( $_POST ) ) {

	$username = mysqli_real_escape_string( $connection, $_POST[ "username" ] );
	$email = mysqli_real_escape_string( $connection, $_POST[ "email" ] );
    $studentid = mysqli_real_escape_string( $connection, $_POST[ "studentid" ] );
    $graduate_by = mysqli_real_escape_string( $connection, $_POST[ "graduate_by" ] );
    $first_name = mysqli_real_escape_string( $connection, $_POST[ "first_name" ] );
    $last_name = mysqli_real_escape_string( $connection, $_POST[ "last_name" ] );

	$password = md5( $_POST[ "password" ] );
	$passwordAgain = md5( $_POST[ "passwordAgain" ] );

	if ( $password == $passwordAgain ) {

		$usernamesql = "SELECT * FROM `users` WHERE username = '$username'";
		$usernameres = mysqli_query( $connection, $usernamesql );
		$count = mysqli_num_rows( $usernameres );
		if ( $count == 1 ) {
			$fmsg = "Username Already Exists! ";
            $error = "true";
            echo $usernamesql;

		}

		$emailsql = "SELECT * FROM `users` WHERE email = '$email'";
        $emailsqlres = mysqli_query( $connection, $emailsql );
        $count = mysqli_num_rows( $emailsqlres );
		if ( $count >= 1 ) {
			$fmsg = "Email Already Exists! ";
            $error = "true";
            echo $emailsql;
		}


		$studentidsql = "SELECT * FROM `users` WHERE studentid = '$studentid'";
		$studentidsqlres = mysqli_query( $connection, $studentidsql );
		$count = mysqli_num_rows( $studentidsqlres );
		if ( $count >= 1 ) {
			$fmsg = "Student ID Already Exists";
            $error = "true";
            echo $studentidsql;

		}

		if ( $error != "true") {

            $hashed_password = sha1($password);


			$sql = "INSERT INTO `users` (username, email, password, studentid, first_name, last_name, graduate_by) VALUES ('$username', '$email', '$hashed_password', '$studentid', '$first_name', '$last_name', '$graduate_by');";

			$result = mysqli_query( $connection, $sql );
			if ( $result ) {
				$smsg = "Success! ";
				
            $recipients = array($email, $nhs_email);
            $subject = "Thank you for registering to Honor Help!";
            $content = "Hey! Thanks for registering to HonorHelp. Make sure to set this email to not spam in order to get all the nessesary emails.";
            tutorsEmail($recipients, $subject, $content);

			} else {
                echo $sql;
            }
        }	

    }

}


?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

<link rel="stylesheet" media="screen" href="login_assets/css/registerstyle.css">



<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width"/>
	<title>Register!</title>
</head>

<body>

	<?php if(isset($smsg)){ ?>
	<div class="alert alert-success" role="alert" style="margin-top: 20px;">
		<?php echo $smsg; ?> </div>
	<?php } ?>

	<?php if(isset($csmsg)){ ?>
	<div class="alert alert-success" role="alert" style="margin-top: 20px;">
		<?php echo $csmsg; ?> </div>
	<?php } ?>

	<?php if(isset($fmsg)){ ?>
	<div class="alert alert-danger" role="alert" style="margin-top: 20px;">
		<?php echo $fmsg; ?> </div>
	<?php } ?>





	<div class="login-container">
		<section class="login" id="login">


			<form class="login-form" action="#" method="post">
            <input type="text" name="first_name" class="login-input" placeholder="First Name" maxlength="100" required/>
            <input type="text" name="last_name" class="login-input" placeholder="Last Name" maxlength="100" required/>

				<input type="text" name="username" class="login-input" placeholder="Username (Anything you want)" maxlength="100" required/>
				<input type="password" name="password" id="input Password" class="login-input" placeholder="Password (keep it secure!)" maxlength="100" required/>
				<input type="email" name="email" id="inputEmail" class="login-input" placeholder="Email address" maxlength="100" required/>
				<input type="text" name="studentid" class="login-input" placeholder="Student Number i.e. 2019108" maxlength="100" required/>

                <input type="number" name="graduate_by" class="login-input" placeholder="Graduating Year, like 2019 or 2020" maxlength="4" required/>


				<input type="password" name="passwordAgain" id="input Password" class="login-input" maxlength="100" placeholder="Password Again" required>
				<button class="btn btn-success">Create Account!</button>

			</form>
		</section>
	</div>


	<p class="message">Already registered? <a href="login.php">Sign In</a>
	</p>


	<script src="js/loginindex.js"></script>

</html>