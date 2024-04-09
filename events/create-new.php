<?php
    session_start();
    require_once '../vendor/autoload.php';
    require_once '../config.php';
    if(!isset($_SESSION['name'])) {
        header('Location: ../login');
    }
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include '../inc/head.php';
    ?>
    <title>New event | EventWave</title>
</head>
<body>
    <?php include '../inc/sidebar.php'; ?>

    <div class="content">
        <div class='wrapper'>
            <div class="d-flex justify-content-between align-items-center title-header">
                <h3>Create new event</h3>
            </div>
            <div class="wrapper-content"></div>
        </div>
    </div>
</body>
</html>
