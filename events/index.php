<?php
    session_start();
    require_once '../vendor/autoload.php';
    require_once '../config.php';
    if(!isset($_SESSION['name'])) {
        header('Location: ../login');
    }
    include '../db_connect/connect.php';
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include '../inc/head.php';
    ?>
    <title>Events | EventWave</title>
</head>
<body>
    <?php include '../inc/sidebar.php'; ?>

    <div class="content">
        <div class='wrapper'>
            <div class="d-flex justify-content-between align-items-center title-header">
                <h3>Events</h3>
                <a href="./create-new">Create new</a>
            </div>
            <div class="events-list">
                <div class="row">
                    <?php
                        $createdBy = $_SESSION['userToken'];
                        $queryEvents = "SELECT * FROM events WHERE created_by = '$createdBy'";
                        $result = mysqli_query($dbc,$queryEvents) or die('Error querying database.');
                        $eventsCounter = 0;
                        if($result) {
                            while($row = mysqli_fetch_array($result)) {
                                $eventsCounter++;
                                $eventName = $row['name'];
                                $eventStart = $row['start_date'];
                                $eventStart = new DateTime($eventStart);
                                $eventStart = $eventStart->format('d.m.Y.');
                                $eventEnd = $row['end_date'];
                                $eventEnd = new DateTime($eventEnd);
                                $eventEnd = $eventEnd->format('d.m.Y.');
                                $eventLocation = $row['location'];
                                $eventCity = $row['city'];
                                $eventCountry = $row['country']; ?>

                                <div class="col-4">
                                    <div class="event-item">
                                        <button type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="../assets/icons/more-menu.svg" />
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                        </ul>
                                        <h5><?php echo $eventName; ?></h5>
                                        <div class="event-item_block">
                                            <span>Start and end date</span>
                                            <p><?php echo $eventStart; ?> - <?php echo $eventEnd; ?></p>
                                        </div>
                                        <div class="event-item_block">
                                            <span>Location</span>
                                            <p><?php echo $eventLocation; ?>, <?php echo $eventCity; ?>, <?php echo $eventCountry; ?></p>
                                        </div>
                                        <div class="event-item_block">
                                            <span>Number of registrations</span>
                                            <p>14/Unlimited</p>
                                        </div>
                                        <div class="event-item_block d-flex flex-column align-items-start">
                                            <span>Status</span>
                                            <p class="status-badge">Scheduled</p>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                        if($eventsCounter == 0) { ?>
                            <div class="col-12">
                                <div class="no-data-message">
                                    <h4>Create your first event</h4>
                                    <p>It's time to make your first event.</p>
                                    <a href="./create-new">Create my first event</a>
                                </div>
                            </div>
                        <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
