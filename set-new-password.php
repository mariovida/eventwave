<?php
    session_start();
    require_once 'vendor/autoload.php';
    require_once 'config.php';

    $error_message = false;
    $user_error = false;
    if(isset($_SESSION['error'])) {
        $error_message = true;
        unset($_SESSION['error']);
    }

	include './db_connect/connect.php';

    if(!isset($_GET['rt'])) {
        header('Location: ./');
    } else {
        $success = false;
        if(isset($_GET['status']) && $_GET['status'] === 'success') {
            $success = true;
        }
        $found = 0;
        $reset_token = $_GET['rt'];
        $queryFindUser = "SELECT id FROM users WHERE password_request_token = '$reset_token'";
        $resultFindUser = mysqli_query($dbc,$queryFindUser) or die('Error querying database.');
        if($resultFindUser) {
            while($row = mysqli_fetch_array($resultFindUser)) {
                $userId = $row['id'];
                $found = 1;
            }
        }
        if($found === 0 && !$success) {
            $user_error = true;
        }
    }
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include 'inc/head.php';
    ?>
    <title>Set new password | EventWave</title>
</head>
<body>
    <section class="auth-screen">
        <div class="row">
            <div class="col-12 col-md-6"></div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                <div class="form-box">
                    <?php if($success) { ?>
                        <h2>Password changed</h2>
                        <p>Your password was updated. You can now log in.</p>
                        <a class="btn-1 mt-4" href="./login">Confirm</a>
                    <?php } ?>
                    <?php if (!$user_error && !$success) { ?>
                        <h2>Set new password</h2>
                        <p>Enter a new password for your account.</p>
                    <?php } ?>
                    <?php if ($error_message) { ?>
                        <div class="verification-error">
                            Passwords do not match. Please try again.
                        </div>
                    <?php } ?>
                    <?php if ($user_error) { ?>
                        <div class="verification-error">
                            There was a problem with the link. Please try again.
                        </div>
                    <?php } ?>
                    <?php if (!$user_error && !$success) { ?>
                        <form action="" method="POST">
                            <input type="password" id="password1" name="password1" placeholder="New password" required>
                            <input type="password" id="password2" name="password2" placeholder="Confirm password" required>
                            <input type="submit" value="Confirm" name="confirm">
                        </form>
                    <?php } ?>

                    <?php
                        if ($dbc && isset($_POST['confirm'])) {
                            $password1 = $_POST['password1'];
                            $password2 = $_POST['password2'];

                            if ($password1 !== $password2) {
                                $_SESSION['error'] = 'error';
                                header('Location: ./set-new-password?rt='.$reset_token);
                            } else {
                                $hashed_password = password_hash($password1, PASSWORD_BCRYPT);
                                $query = "UPDATE users SET password = '$hashed_password', password_request_token = '' WHERE password_request_token = '$reset_token'";
                                $result = mysqli_query($dbc, $query);
                                if (!$result) {
                                    die('Error: ' . mysqli_error($dbc));
                                } else {
                                    $_SESSION['changed'] = 'yes';
                                    header('Location: ./set-new-password?rt=done&status=success');
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
