<?php
require_once('../../assets/includes/dbh.inc.php');
require_once('../../assets/includes/email_functions.php');


if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string( $connection, $_POST[ 'email' ] );
    $password = mysqli_real_escape_string( $connection, $_POST[ 'password' ] );
    $randString = mysqli_real_escape_string( $connection, $_POST[ 'randString' ] );

    $recipient = array($email);
    $subject = "Password Recently Changed";
    $content = "Dear User, <br><br>
       Your password was recently changed. Just saying. 
        <br>

        Thanks,
        <br> Nick";


    $sql = "SELECT * FROM password_recovery WHERE RandomString = '$randString' AND associated_email = '$email'";
    $result = mysqli_query($connection, $sql);
    if(mysqli_num_rows($result) > 0){

        $password1 = md5($password);
        $md5_hash_password = sha1($password1);
        echo $sql2 = "UPDATE `users` SET password = '$md5_hash_password' WHERE email = '$email'";
        $result2 = mysqli_query($connection, $sql2);
        if($result2){
            $email_sent = tutorsEmail($recipient, $subject, $content);
            if($email_sent){
            Redirect("login");
        }
        }
    } else {
        echo "<h1> Please go and request a token to reset your password. </h1>";
    }

}
?>



<h1>Fill out this form and get on with your life nerd.</h1>
<p> Yeah yeah I know this isn't secure but who cares. If ur skilled enough to intercept the packets and change passwords then go do something more productive than bullying this website. 
</p>

<form method="POST">
    <input type="email" placeholder="Type your email here" name="email" required>
    <br><br>
    <input type="text" placeholder="Copy and paste the random string" name="randString" required>
    <br><br>
    <input type="password" placeholder="new password" name="password" required>
    <br><br>
    <button name="submit">Submit</button>
</form>

<a href="forgot_password">If you haven't requested a token yet please go here. </a>


<p>see now do u have an appreciation for how pretty I make things look with all that fancy CSS and Javascript?</p>
<p>Anyway I didnt bother to make this page look nice cuz shame on you for losing ur password.</p>