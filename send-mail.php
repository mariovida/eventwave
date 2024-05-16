<?php
    session_start();

    $emailTo = $_POST['email'];
    $emailSubject = $_POST['subject'];
    $emailBody = $_POST['message'];

    $_SESSION['emailBody'] = $emailBody;
    echo '<script>window.open("./display-page.php", "_blank");</script>';
    exit;
    
    /*use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';

    // Retrieve form data
    $emailTo = $_POST['email'];
    $emailSubject = $_POST['subject'];
    $emailBody = $_POST['message'];

    $emailFrom = 'hello@mario-dev.eu';
    $emailFromName = 'Mario';
    
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
    $mail->Host = 'smtp.zoho.eu';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = 'hello@mario-dev.eu';
    $mail->Password = '63098118#Abc';
    $mail->setFrom($emailFrom, $emailFromName);
    $mail->addAddress($emailTo);
    $mail->Subject = $emailSubject;
    $mail->isHTML(true);
    $mail->Body = $emailBody;

    if ($mail->send()) {
        header("Location: ./");
    } else {
        echo 'Email sending failed: ' . $mail->ErrorInfo;
    }*/