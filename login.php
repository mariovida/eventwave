<?php
    session_start();
    require_once 'vendor/autoload.php';
    require_once 'config.php';
    if(isset($_SESSION['name'])) {
        header('Location: ./');
    }
    $error_message_verification = false;
    $error_message = false;
    if(isset($_SESSION['error'])) {
        $error_message = true;
        $email_set = $_SESSION['error'];
        unset($_SESSION['error']);
    }
	include './db_connect/connect.php';

    if ($dbc && isset($_POST['login'])) {
        $user = $_POST['email'];
        $pass = $_POST['password'];
        $sql = "SELECT id, first_name, password, is_verified FROM users WHERE email = ?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $user);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }
        mysqli_stmt_bind_result($stmt, $userId, $userName, $userPass, $isVerified);
        mysqli_stmt_fetch($stmt);
        if (password_verify($_POST['password'], $userPass) && mysqli_stmt_num_rows($stmt) > 0) {
            if ($isVerified == 1) {
                $login_success = true;
                $_SESSION['name'] = $userName;
                $_SESSION['userToken'] = $userId;
                //$_SESSION['last_activity'] = time();
                //$_SESSION['expire_time'] = 5; // 60 = 1 minute
                header('Location: ./');
            } else {
                $login_success = false;
                $error_message_verification = true;
            }
        } else {
            $_SESSION['error'] = $user;
            header('Location: ./login');
            $login_success = false;
            $error_message = true;
        }
    }
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include 'inc/head.php';
    ?>
    <title>Login | EventWave</title>
</head>
<body>
    <section class="auth-screen">
        <div class="row">
            <div class="col-12 col-md-6"></div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                <div class="form-box">
                    <h2>Log in</h2>
                    <p>Don't have an account? <a href="./registration" style="text-decoration:underline">Register now</a>.</p>
                    <?php if ($error_message_verification) { ?>
                        <div class="verification-error">
                            Your account hasn't been verified.<br/>Please check your email for verification instructions.
                        </div>
                    <?php } ?>
                    <?php if ($error_message) { ?>
                        <div class="verification-error">
                            Entered password is incorrect.
                        </div>
                    <?php } ?>
                    <form action="" method="POST">
                        <input type="email" id="email" name="email" placeholder="Email address" <?php if ($error_message) {
                            echo "value='$email_set'"; } ?> required>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <input type="submit" value="Log in" name="login">
                    </form>
                    <a href="./forgot-password" class="mt-5">Forgot password?</a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
