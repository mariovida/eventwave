<?php
    session_start();
    require_once 'vendor/autoload.php';
    require_once 'config.php';
    include './db_connect/connect.php';

    $error_message = false;
    if(isset($_SESSION['error'])) {
        $error_message = true;
        unset($_SESSION['error']);
    }
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include 'inc/head.php';
    ?>
    <title>Reset password | EventWave</title>
</head>
<body>
    <section class="auth-screen">
        <div class="row">
            <div class="col-12 col-md-6"></div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                <div class="form-box">
                    <a href="./login" class="back-button"><?php include './assets/icons/arrow-left.svg'; ?> Back to login</a>
                    <h2>Reset password</h2>
                    <p>Please enter your email address to request a password reset.</p>
                    <?php if ($error_message) { ?>
                        <div class="verification-error">
                            We couldn't find an account associated with that email address.
                        </div>
                    <?php } ?>
                    <form action="" method="POST">
                        <input type="email" id="email" name="email" placeholder="Email address" required>
                        <input type="submit" value="Confirm" name="confirm">
                    </form>

                    <?php
                        if ($dbc && isset($_POST['confirm'])) {
                            include './services/auth.php';
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
