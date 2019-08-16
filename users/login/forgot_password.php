<?php
require_once('../../assets/includes/dbh.inc.php');
require_once('../../assets/includes/email_functions.php');

$n=30; 
function getName($n) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
  
    return $randomString; 
} 
  


if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string( $connection, $_POST[ 'email' ] );
    $random_string = getName($n); 

    $recipient = array($email);
    $subject = "Forgot Password";
    $content = "Dear User, <br><br>
        Take this string of characters and go here: tutors.concordiashanghai.org/users/login/forgot_password_recovery
        <br> and reset your password. 
        <br>
        Random String: $random_string

        Thanks,
        <br> Nick
    ";

    $sql = "INSERT INTO `password_recovery` (RandomString, associated_email) VALUES ('$random_string', '$email') ";
    $result = mysqli_query($connection, $sql);
    if($result){
        $email_sent = tutorsEmail($recipient, $subject, $content);
        if($email_sent){
            echo "<h1> GO CHECK YOUR EMAIL. </h1>";
            Redirect("forgot_password_recovery");
        }
    }

}
?>



<h1>Great job you forgot your password.</h1>
<p>I'm kinda tired so this page ain't gonna look so pretty.
</p>

<form method="POST">
    <input type="email" placeholder="Type your email here" name="email" required>
    <br><br>
    <button name="submit">Submit</button>
</form>


<p>see now do u have an appreciation for how pretty I make things look with all that fancy CSS and Javascript?</p>
<p>Anyway I didnt bother to make this page look nice cuz shame on you for losing ur password.</p>