<?php
    session_start();
    require_once 'vendor/autoload.php';
    require_once 'config.php';
	include './db_connect/connect.php';
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include 'inc/head.php';
    ?>
    <title>Registration | EventWave</title>
</head>
<body>
    <section class="auth-screen">
        <div class="row">
            <div class="col-12 col-md-6"></div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                <div class="form-box">
                    <a href="./login" class="back-button"><?php include './assets/icons/arrow-left.svg'; ?> Back to login</a>
                    <h2>Registration</h2>
                    <p>Ready to join? Fill out the form and we will send you a confirmation email.</p>
                    <form action="" method="POST">
                        <div class="form-row">
                            <input type="text" id="first_name" name="first_name" placeholder="First name" required>
                            <input type="text" id="last_name" name="last_name" placeholder="Last name" required>
                        </div>
                        <input type="email" id="email" name="email" placeholder="Email address" required>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <input type="submit" value="Register" name="register">
                    </form>

                    <?php
                        if ($dbc && isset($_POST['register'])) {
                            include './services/verify-account.php';

                            $first_name = $_POST['first_name'];
                            $last_name = $_POST['last_name'];
                            $email = $_POST['email'];
                            $password = $_POST['password'];
                            $hashed_password = password_hash($password, CRYPT_BLOWFISH);
                            $date = date("Y-m-d H:i:s");
                            $role = 2;
                            $is_verified = 0;
                            $register = true;
                            $randomBytes = random_bytes(18);
                            $userToken = bin2hex($randomBytes);
                
                            $queryCheckMail = "SELECT * FROM users WHERE email = '$email'";
                            $resultCheckMail = mysqli_query($dbc,$queryCheckMail) or die('Error querying database.');
                            if($resultCheckMail) {
                                while($row = mysqli_fetch_array($resultCheckMail)) {
                                    echo "<p class='error-message' style='color:#EA212D;font-weight:bold;padding-top:10px;text-align:center'>Email adresa je već registrirana!</p>";
                                    $register = false;
                                }
                            }

                            if($register == true) {
                                $sql = "INSERT INTO users (first_name, last_name, email, password, role, date_created, is_verified, user_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt = mysqli_stmt_init($dbc);
                                if (mysqli_stmt_prepare($stmt, $sql)) {
                                    mysqli_stmt_bind_param($stmt, 'ssssssss', $first_name, $last_name, $email, $hashed_password, $role, $date, $is_verified, $userToken);
                                    if(mysqli_stmt_execute($stmt)) {
                                        $sendEmailResult = sendVerificationEmail($email, $userToken);
                                        if ($sendEmailResult === true) {
                                            header("Location: ./registration-confirm");
                                            exit;
                                        } else {
                                            echo $sendEmailResult;
                                        }
                                    }
                                }
                            }
                        }
                        mysqli_close($dbc);
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
