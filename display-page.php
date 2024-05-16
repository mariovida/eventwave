<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include 'inc/head.php';
    ?>
    <title>Email Template</title>
</head>
<body>
    <?php
        session_start();
        $emailBody = isset($_SESSION['emailBody']) ? $_SESSION['emailBody'] : '';
        echo '<div class="display-page">'.$emailBody.'</div>';
    ?>
</body>
</html>