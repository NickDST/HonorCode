<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
// require 'vendor/autoload.php';
require '../../vendor/autoload.php';
//create an email function that takes in recipient(s), subject, content

$nhs_email = 'nicholas2019108@concordiashanghai.org';


function tutorsEmail($recipients, $subject, $content){
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    //$mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'tutors@concordiashanghai.org';                 // SMTP username
    $mail->Password = 'TTS@shanghai18';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('tutors@concordiashanghai.org', 'HonorCode');
    // $mail->addAddress('nicholas2019108@concordiashanghai.org', 'Joe User');     // Add a recipient
    foreach ($recipients as $user) {
        // echo "Current value of \$a: $v.\n";
        // echo "Mailing to: " . $user;
        $mail->addAddress($user);     
    }

  //  $mail->addAddress('ellen@example.com');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //  $mail->addCC('cc@example.com');
  //  $mail->addBCC('bcc@example.com');
    
  //Attachments
  //  $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
  //  $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $content;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
    return True;
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    return False;
}
}

// $recipients = array('nicholas2019108@concordiashanghai.org', 'learningnickk@gmail.com');
// $subject = "First function email test - Nick";
// $content = "<strong> This is the first function test!</strong>";

//tutorsEmailing($recipients, $subject, $content);



?>