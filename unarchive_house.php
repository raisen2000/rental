<?php
// Include database connection
include 'db_connect.php';

// Check if 'id' is provided in the request
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitize the input

    // Update the 'archive' status of the house to 0 (unarchived)
    $query = "UPDATE houses SET archive = 0 WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Successfully unarchived
            echo json_encode(['success' => true, 'message' => 'House successfully unarchived.']);
        } else {
            // Failed to execute the query
            echo json_encode([
                'success' => false,
                'message' => 'SQL Error: ' . $query . ' - ' . $stmt->error
            ]);
        }

        $stmt->close();
    } else {
        // Failed to prepare the query
        echo json_encode([
            'success' => false,
            'message' => 'SQL Preparation Error: ' . $query . ' - ' . $conn->error
        ]);
    }
} else {
    // Invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request. House ID is missing.']);
}

// Close the database connection
$conn->close();
