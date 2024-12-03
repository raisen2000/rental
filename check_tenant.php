<?php
include('db_connect.php');

if (isset($_POST['id'])) {
    $house_id = $_POST['id'];
    $query = $conn->query("SELECT COUNT(*) as tenant_count FROM tenants WHERE house_id = $house_id");
    $result = $query->fetch_assoc();

    if ($result['tenant_count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'This house cannot be archived because it has tenants.']);
    } else {
        echo json_encode(['success' => true]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
