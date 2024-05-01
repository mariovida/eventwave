<?php
    $APP_URL = "https://mario-dev.eu/construction/events";
    $TEST_URL = "http://localhost/events";
    $pageUrl = $TEST_URL;

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/events/uploads/';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if file is uploaded
        if (!empty($_FILES['filepond'])) {
            $file = $_FILES['filepond'];
    
            // Generate a unique name for the file
            $fileName = uniqid() . '-' . basename($file['name']);
            $uploadFilePath = $uploadDir . $fileName;
    
            // Ensure the upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            // Move the uploaded file to the target directory
            if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                // Return the file path or some identifier
                // echo json_encode(['key' => $fileName]);
                echo json_encode(['status' => 'success', 'url' => $fileName]);
            } else {
                // Return an error response
                http_response_code(500);
                echo json_encode(['data' => 'Failed to move uploaded file.']);
            }
        } else {
            // Return an error response
            http_response_code(400);
            echo json_encode(['data' => 'No file uploaded.']);
        }
    } else {
        // Return an error response
        http_response_code(405); // Method Not Allowed
        echo json_encode(['data' => 'Invalid request method.']);
    }
