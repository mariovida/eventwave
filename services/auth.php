<?php
    if(!isset($_POST['confirm']) || !isset($_POST['email']) || empty($_POST['email'])) {
        header('Location: ./');
        exit; 
    }

    require_once './vendor/autoload.php';
    require_once './config.php';

    if ($dbc) {
        $user = $_POST['email'];
        $sql = "SELECT email FROM users WHERE email = ?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $user);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }
        mysqli_stmt_bind_result($stmt, $userMail);
        mysqli_stmt_fetch($stmt);

        if(mysqli_stmt_num_rows($stmt) > 0) {
            $emailFound = 1;
        } else {
            $emailFound = 0;
        }
    }

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';

    if($emailFound == 1) {
        $emailTo = $_POST['email'];
        $emailFrom = $_ENV['SMTP_USERNAME'];
        $emailFromName = 'EventWave';
        $emailSubject = "Password reset link";
        $emailBody = file_get_contents('./templates/password-reset.html');
    
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 4; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->Port = 587;
        $mail->SMTPSecure = 'none';
        $mail->SMTPAutoTLS = false;
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
            header("Location: ./forgot-password-confirm");
        } else {
            echo 'Email sending failed: ' . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error'] = true;
        header("Location: ./forgot-password");
    }
