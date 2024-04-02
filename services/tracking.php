<?php
    session_start();
    require_once '../vendor/autoload.php';
    require_once '../config.php';
    include '../db_connect/connect.php';

    if ($dbc) {
        $userId = 8;
        $query = "UPDATE users SET is_verified = 9 WHERE id = '$userId'";
        $result = mysqli_query($dbc,$query) or die('Error querying database.');
    }