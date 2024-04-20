<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config.php';
if(!isset($_SESSION['name'])) {
    header('Location: ../login');
}

include '../db_connect/connect.php';
if ($dbc && isset($_POST['create-event'])) {
    $eventName = $_POST['event_name'];
    $eventDescription = $_POST['event_description'];
    $date = date("Y-m-d H:i:s");
    $eventImage = $_POST['event_image'];

    $sql = "INSERT INTO events (name, description, header_image, date_created) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssss', $eventName, $eventDescription, $eventImage, $date);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: ./');
            exit;
        } else {
            echo "Database insertion error: " . mysqli_stmt_error($stmt);
            error_log("Database insertion error: " . mysqli_stmt_error($stmt));
        }
    } else {
        echo "Database prepare error: " . mysqli_error($dbc);
        error_log("Database prepare error: " . mysqli_error($dbc));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    define('meta', TRUE);
    include '../inc/head.php';
    ?>
    <!-- include FilePond library -->
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet">
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <title>New event | EventWave</title>
</head>
<body>
<?php include '../inc/sidebar.php'; ?>

<div class="content">
    <div class='wrapper'>
        <div class="back-btn">
            <a href="./"><?php include '../assets/icons/arrow-left.svg'; ?>All events</a>
        </div>
        <div class="d-flex justify-content-between align-items-center title-header">
            <h3>Create new event</h3>
        </div>
        <div class="wrapper-content events-create-new">
            <form id="event-form" action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="event_image" class="event-image">
                <input type="file" class="filepond event-header-image" name="filepond">
                <h5>Event information</h5>
                
                <div class="form-row">
                    <input type="text" name="event_name" placeholder="Event name" required>
                    <input type="text" name="event_organizer" placeholder="Organizer" required>
                </div>
                <div class="form-row">
                    <textarea name="event_description" placeholder="Event Description" rows="5" required></textarea>
                </div>
                <input type="submit" value="Create event" name="create-event">
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        FilePond.registerPlugin(
            FilePondPluginFileEncode,
            FilePondPluginFileValidateSize,
            FilePondPluginImageExifOrientation,
            FilePondPluginImagePreview
        );
        const pond = FilePond.create(document.querySelector('input.filepond'), {
            server: {
                process: {
                    url: './helpers/upload.php',
                    onload: (response) => {
                        const res = JSON.parse(response);
                        document.querySelector('input.event-image').value = res.key;
                    }
                }
            }
        });
    });
</script>
</body>
</html>
