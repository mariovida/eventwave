<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config.php';
if(!isset($_SESSION['name'])) {
    header('Location: ../login');
}

include '../db_connect/connect.php';
if ($dbc && isset($_POST['create-event'])) {
    $eventType = $_POST['event_type'];
    $eventName = $_POST['event_name'];
    $eventCategory = $_POST['event_category'];
    $eventDescription = $_POST['event_description'];
    $eventOrganizer = $_POST['event_organizer'];
    $eventTimezone = $_POST['event_timezone'];
    $eventStart = $_POST['start_date'];
    $eventEnd = $_POST['end_date'];
    $eventLocation = $_POST['event_location'];
    $eventAddress = $_POST['event_address'];
    $eventCity = $_POST['event_city'];
    $eventCountry = $_POST['event_country'];
    $date = date("Y-m-d H:i:s");
    $eventImage = $_POST['event_image'];
    $randomBytes = random_bytes(16);
    $randomString = bin2hex($randomBytes);

    $sql = "INSERT INTO events (event_id, name, description, type, category, organizer, timezone, start_date, end_date, location, address, city, country, header_image, date_created, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssssssssssssssss', $randomString, $eventName, $eventDescription, $eventType, $eventCategory, $eventOrganizer, $eventTimezone, $eventStart, $eventEnd, $eventLocation, $eventAddress, $eventCity, $eventCountry, $eventImage, $date, $_SESSION['userToken']);
        if (mysqli_stmt_execute($stmt)) {
            echo 'Data saved!';
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
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
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
                <div id="uploaded-image-container">
                    <img id="uploaded-image" src="" alt="Uploaded Image" />
                </div>
                <h5>Event information</h5>

                <h5 style="font-size:16px;font-weight:500;margin-bottom:16px">Event type</h5>
                <div class="form-row mb-4">
                    <div>
                        <input type="radio" id="public" name="event_type" value="public" required>
                        <label for="public">Public</label>
                    </div>
                    <div>
                        <input type="radio" id="private" name="event_type" value="private" required>
                        <label for="private">Private</label>
                    </div>
                </div>
                <div class="form-row">
                    <input type="text" name="event_name" placeholder="Event name" required>
                    <select name="event_category" required>
                        <option value="">Event category</option>
                        <option value="workshop">Workshop</option>
                        <option value="seminar">Seminar</option>
                        <option value="concert">Concert</option>
                        <option value="conference">Conference</option>
                    </select>
                </div>
                <div class="form-row">
                    <textarea name="event_description" placeholder="Event Description" rows="5" required></textarea>
                </div>
                <div class="form-row">
                    <input type="text" name="event_organizer" placeholder="Organizer" required>
                    <select name="event_timezone" id="event_timezone">
                        <option value="" selected disabled>Timezone</option>
                        <?php
                        $timezones = timezone_identifiers_list();
                        foreach($timezones as $timezone) {
                            echo "<option value=\"$timezone\">$timezone</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-row">
                    <input type="text" id="start_date" name="start_date" placeholder="Start date" onfocus="(this.type='datetime-local')" required>
                    <input type="text" id="end_date" name="end_date" placeholder="End date" onfocus="(this.type='datetime-local')" required>
                </div>

                <h5 class="mt-5">Registration and attendance information</h5>
                <div class="form-row">
                    <input type="text" name="event_location" placeholder="Location name" required>
                    <input type="text" name="event_address" placeholder="Address">
                </div>
                <div class="form-row">
                    <input type="text" name="event_city" placeholder="City" required>
                    <select name="event_country" id="event_country" required>
                        <option value="" selected disabled>Country</option>
                    </select>
                </div>

                <h5 class="mt-5">Tickets</h5>
                <button type="button" class="btn-2" data-bs-toggle="modal" data-bs-target="#addTicketModal">
                    Add ticket
                </button>
                <!-- Add ticket modal -->
                <div class="modal fade" id="addTicketModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addTicketModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content add-ticket-modal">
                        <div class="modal-header">
                            <h4 class="modal-title" id="addTicketModalLabel">Add ticket</h4>
                        </div>
                        <div class="modal-body">
                            <h5 style="font-size:16px;font-weight:500;margin-bottom:16px">Ticket type</h5>
                            <div class="form-row mb-4">
                                <div class="radio-div">
                                    <input type="radio" id="free" name="ticket_type" value="free">
                                    <label for="free">Free ticket</label>
                                </div>
                                <div class="radio-div">
                                    <input type="radio" id="paid" name="ticket_type" value="paid">
                                    <label for="paid">Paid ticket</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-start">
                            <button type="button" class="discard-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="submit-btn">Add ticket</button>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-4 mt-4">
                    <button type="button" class="discard-btn" data-bs-toggle="modal" data-bs-target="#discardModal">Discard event</button>
                    <input type="submit" value="Create event" name="create-event">
                </div>
                <!-- Discard event modal -->
                <div class="modal fade" id="discardModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="discardModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content discard-modal">
                        <div class="modal-body">
                            <div>
                                <?php include '../assets/icons/warning.svg'; ?>
                            </div>
                            <div>
                                <h4>Discard event</h4>
                                <p>Are you sure you want to discard creating a new event?</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="discard-btn" data-bs-dismiss="modal">Cancel</button>
                            <a href="./" type="button" class="submit-btn">Discard</a>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateTimeInput = document.getElementById('start_date');
        const endDateTimeInput = document.getElementById('end_date');

        startDateTimeInput.addEventListener('change', function() {
            endDateTimeInput.min = startDateTimeInput.value;
        });

        endDateTimeInput.addEventListener('change', function() {
            startDateTimeInput.max = endDateTimeInput.value;
        });

        fetch('../lists/countries.json')
        .then(response => response.json())
        .then(data => {
            const countrySelect = document.getElementById('event_country');
            data.forEach(country => {
                const option = document.createElement('option');
                option.value = country.name;
                option.textContent = country.name;
                countrySelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching country data:', error));

        FilePond.registerPlugin(
            FilePondPluginFileEncode,
            FilePondPluginFileValidateSize,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateType
        );
        const pond = FilePond.create(document.querySelector('input.filepond'), {
            server: {
                process: {
                    url: './helpers/upload.php',
                    onload: (response) => {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            const uploadedImageUrl = res.url;
                            document.querySelector('input.event-image').value = uploadedImageUrl;
                            //document.querySelector('.event-header-image').style.display = 'none';
                            document.getElementById('uploaded-image').src = '../uploads/' + uploadedImageUrl;
                            document.getElementById('uploaded-image-container').style.display = 'block';
                        } else {
                            console.error('Upload failed:', res.message);
                        }
                    }
                }
            },
            instantUpload: true,
            maxFiles: 1,
            maxFileSize: '1MB',
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/jpeg', 'image/png'],
            labelIdle: 'Drag & Drop your image or <span class="filepond--label-action">Browse</span>',
            labelFileWaitingForSize: 'Processing...',
            labelFileSizeNotAvailable: 'File size is not available',
            labelFileLoading: 'Loading...',
            labelFileLoadError: 'Error while loading file',
            labelFileProcessing: 'Uploading...',
            labelFileProcessingComplete: 'Upload complete',
            labelFileProcessingAborted: 'Upload cancelled',
            labelFileProcessingError: 'Error during upload',
            labelFileProcessingRevertError: 'Error while reverting upload',
            labelTapToCancel: 'tap to cancel',
            labelTapToRetry: 'tap to retry',
            labelTapToUndo: 'tap to undo',
            labelButtonRemoveItem: 'Remove',
            labelButtonAbortItemLoad: 'Abort',
            labelButtonRetryItemLoad: 'Retry',
            labelButtonAbortItemProcessing: 'Cancel',
            labelButtonUndoItemProcessing: 'Undo',
            labelButtonRetryItemProcessing: 'Retry',
            labelButtonProcessItem: 'Upload'
        });
        pond.on('addfile', () => {
            document.querySelector('.event-header-image').style.display = 'none';
        });
    });
</script>
</body>
</html>
