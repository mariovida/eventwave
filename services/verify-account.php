<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';

    function sendVerificationEmail($emailTo, $userToken)
    {
        $randomBytes = random_bytes(16);
        $randomString = bin2hex($randomBytes);
        echo $randomString;

        $emailFrom = $_ENV['SMTP_USERNAME'];
        $emailFromName = 'EventWave';
        $emailSubject = "Verify your account";
        $emailBody = file_get_contents('./templates/registration-verify.html');
        $emailBody = str_replace('{{user_token}}', $userToken, $emailBody);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->Port = 587;
        $mail->SMTPAutoTLS = true;
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->setFrom($emailFrom, $emailFromName);
        $mail->addAddress($emailTo);
        $mail->Subject = $emailSubject;
        $mail->isHTML(true);
        $mail->Body = $emailBody;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ];

        if ($mail->send()) {
            return true;
        } else {
            echo 'Email sending failed: ' . $mail->ErrorInfo;
        }
    }