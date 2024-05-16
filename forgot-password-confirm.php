<?php
    session_start();
    require_once 'vendor/autoload.php';
    require_once 'config.php';
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include 'inc/head.php';
    ?>
    <title>Email sent | EventWave</title>
</head>
<body>
    <section class="auth-screen">
        <div class="row">
            <div class="col-12 col-md-6"></div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                <div class="form-box">
                    <h2>Email has been sent</h2>
                    <p>Please check your inbox and click the link to reset your password.</p>
                    <a class="btn-1 mt-4" href="./login">Confirm</a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
