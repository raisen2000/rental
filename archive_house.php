<?php
include('db_connect.php');

if (isset($_POST['id']) && isset($_POST['action'])) {
    $house_id = $_POST['id'];
    $action = $_POST['action']; // 'archive' or 'unarchive'

    if ($action === 'archive') {
        // Check if the house has tenants before archiving
        $tenant_check = $conn->query("SELECT COUNT(*) as tenant_count FROM tenants WHERE house_id = $house_id");
        $tenant_result = $tenant_check->fetch_assoc();

        if ($tenant_result['tenant_count'] > 0) {
            echo json_encode(['success' => false, 'message' => 'This house cannot be archived because it has tenants.']);
            exit;
        }

        $query = $conn->query("UPDATE houses SET archive = 1 WHERE id = $house_id");
    } elseif ($action === 'unarchive') {
        // Allow unarchiving without checking tenants
        $query = $conn->query("UPDATE houses SET archive = 0 WHERE id = $house_id");
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        exit;
    }

    if ($query) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update house status.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
