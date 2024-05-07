<?php
    session_start();
    require_once '../vendor/autoload.php';
    require_once '../config.php';
    if(!isset($_SESSION['name'])) {
        header('Location: ../login');
    }
    if(!isset($_GET['eid'])) {
        header('Location: ./');
    }

    $eventId = $_GET['eid'];
    include '../db_connect/connect.php';
    $queryEvent = "SELECT * FROM events WHERE event_id = '$eventId'";
    $result = mysqli_query($dbc,$queryEvent) or die('Error querying database.');
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $eventName = $row['name'];
            $eventOrganizer = $row['organizer'];
            $eventStart = $row['start_date'];
            $eventStart = new DateTime($eventStart);
            $eventStart = $eventStart->format('d.m.Y.');
            $eventEnd = $row['end_date'];
            $eventEnd = new DateTime($eventEnd);
            $eventEnd = $eventEnd->format('d.m.Y.');
            $eventLocation = $row['location'];
            $eventCity = $row['city'];
            $eventCountry = $row['country'];
            $eventImage = $row['header_image'];
        }
    }
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include '../inc/head.php';
    ?>
    <title><?php echo $eventName; ?> | EventWave</title>
</head>
<body>
    <?php include '../inc/sidebar.php'; ?>

    <div class="content">
        <div class='wrapper'>
            <div class="back-btn">
                <a href="./"><?php include '../assets/icons/arrow-left.svg'; ?>All events</a>
            </div>
            <div class="events-statistics">
                <div class="header-image">
                    <img src="../uploads/<?php echo $eventImage; ?>" />
                </div>
                <div class="d-flex flex-column header">
                    <p><?php echo $eventOrganizer ?></p>
                    <h3><?php echo $eventName; ?></h3>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
