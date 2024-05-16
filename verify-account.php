<?php
    session_start();
    require_once 'vendor/autoload.php';
    require_once 'config.php';
    include './db_connect/connect.php';

    if(!isset($_GET['t'])) {
        header('Location: ./');
        
    } else {
        $token = $_GET['t'];
        if ($dbc) {
            $found = 0;
            $queryFindUser = "SELECT id, is_verified FROM users WHERE user_token = '$token'";
            $resultFindUser = mysqli_query($dbc,$queryFindUser) or die('Error querying database.');
            if($resultFindUser) {
                while($row = mysqli_fetch_array($resultFindUser)) {
                    $userId = $row['id'];
                    $checkVerified = $row['is_verified'];
                    $found = 1;
                }
            }
            if($found === 0) {
                header('Location: ./');
            }
            if($found === 1) {
                $query = "UPDATE users SET is_verified = 1 WHERE id = '$userId'";
                $result = mysqli_query($dbc,$query) or die('Error querying database.');
            }
        } else {
            echo "Error connecting to the database";
        }
    }
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include 'inc/head.php';
    ?>
    <title>Account verified | EventWave</title>
</head>
<body>
    <section class="auth-screen">
        <?php if(isset($_GET['t'])) { ?>
            <div class="row">
                <div class="col-12 col-md-6"></div>
                <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                    <div class="form-box">
                        <?php if($checkVerified == 1) { ?>
                            <h2>Account already verified...</h2>
                            <p>Your account was already verified. Feel free to log in and enjoy our services.</p>
                        <?php } else { ?>
                            <h2>Account verified</h2>
                            <p>You're officially a member now. Feel free to log in and enjoy our services.</p>
                        <?php } ?>
                        <a class="btn-1 mt-4" href="./login">Confirm</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
</body>
</html>
