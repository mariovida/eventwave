<?php
    if(!isset($_POST['confirm']) || !isset($_POST['email']) || empty($_POST['email'])) {
        header('Location: ./');
        exit; 
    }

    require_once './vendor/autoload.php';
    require_once './config.php';

    if ($dbc) {
        $user = $_POST['email'];
        $sqlSelect = "SELECT id, email FROM users WHERE email = ?";
        $stmtSelect = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmtSelect, $sqlSelect)) {
            mysqli_stmt_bind_param($stmtSelect, 's', $user);
            mysqli_stmt_execute($stmtSelect);
            mysqli_stmt_store_result($stmtSelect);
        }
        mysqli_stmt_bind_result($stmtSelect, $userId, $userMail);
        mysqli_stmt_fetch($stmtSelect);

        if(mysqli_stmt_num_rows($stmtSelect) > 0) {
            $emailFound = 1;
            $randomBytes = random_bytes(18);
            $userToken = bin2hex($randomBytes);
            $queryInsert = "UPDATE users SET password_request_token = '$userToken' WHERE id = '$userId'";
            $resultInsert = mysqli_query($dbc,$queryInsert) or die('Error querying database.');
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
        $emailBody = str_replace('{{reset_token}}', $userToken, $emailBody);
    
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
            header("Location: ./forgot-password-confirm");
        } else {
            echo 'Email sending failed: ' . $mail->ErrorInfo;
            header("Location: ./forgot-password");
        }
    } else {
        $_SESSION['error'] = true;
        header("Location: ./forgot-password");
    }
