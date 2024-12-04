<?php
include 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$tenant = [];
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM tenants WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tenant = $result->fetch_assoc();
    $stmt->close();
}

$houses = [];
$stmt = $conn->prepare("
    SELECT * FROM houses 
    WHERE id NOT IN (SELECT house_id FROM tenants WHERE status = 1) AND archive = 0 
    OR id = ?
");
$house_id = isset($tenant['house_id']) ? $tenant['house_id'] : null;
$stmt->bind_param("i", $house_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $houses[] = $row;
}
$stmt->close();

if (empty($houses)) {
    echo "<script>
        alert('No houses are currently available for tenants.');
        window.history.back();
    </script>";
    exit;
}
?>
<div class="container-fluid">
    <form action="" id="manage-tenant">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <div class="row form-group">
            <div class="col-md-4">
                <label for="lastname" class="control-label">Last Name</label>
                <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($tenant['lastname'] ?? ''); ?>" required>
            </div>
            <div class="col-md-4">
                <label for="firstname" class="control-label">First Name</label>
                <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($tenant['firstname'] ?? ''); ?>" required>
            </div>
            <div class="col-md-4">
                <label for="middlename" class="control-label">Middle Name</label>
                <input type="text" class="form-control" name="middlename" value="<?php echo htmlspecialchars($tenant['middlename'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="email" class="control-label">Facebook Name</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($tenant['email'] ?? ''); ?>" required>
            </div>
            <div class="col-md-4">
                <label for="contact" class="control-label">Contact #</label>
                <input type="text" class="form-control" name="contact" value="<?php echo htmlspecialchars($tenant['contact'] ?? ''); ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="house_ids" class="control-label">House(s)</label>
                <select name="house_ids[]" id="house_ids" class="custom-select select2" multiple>
                    <option value=""></option>
                    <?php foreach ($houses as $house): ?>
                        <option value="<?php echo $house['id']; ?>" 
                            <?php echo isset($tenant['house_id']) && $tenant['house_id'] == $house['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($house['house_no']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="date_in" class="control-label">Registration Date</label>
                <input type="date" class="form-control" name="date_in" value="<?php echo isset($tenant['date_in']) ? date("Y-m-d", strtotime($tenant['date_in'])) : ''; ?>" required>
            </div>
        </div>
    </form>
</div>

<script>
    $('#manage-tenant').submit(function(e) {
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_tenant',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully saved.", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    alert_toast("An error occurred. Please try again.", 'danger');
                }
                end_load();
            },
            error: function() {
                alert_toast("An error occurred. Please try again.", 'danger');
                end_load();
            }
        });
    });
</script>
