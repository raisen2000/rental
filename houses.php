<?php include('db_connect.php'); ?>

<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<form action="" id="manage-house">
					<div class="card">
						<div class="card-header">
							House Form
						</div>
						<div class="card-body">
							<div class="form-group" id="msg"></div>
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">House No</label>
								<input type="text" class="form-control" name="house_no" required="">
							</div>
							<div class="form-group">
								<label class="control-label">Category</label>
								<select name="category_id" id="" class="custom-select" required>
									<?php
									$categories = $conn->query("SELECT * FROM categories order by name asc");
									if ($categories->num_rows > 0):
										while ($row = $categories->fetch_assoc()) :
									?>
											<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
										<?php endwhile; ?>
									<?php else: ?>
										<option selected="" value="" disabled="">Please check the category list.</option>
									<?php endif; ?>
								</select>
							</div>
							<div class="form-group">
								<label for="" class="control-label">Description</label>
								<textarea name="description" id="" cols="30" rows="4" class="form-control" required></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" class="form-control text-right" name="price" step="any" required="">
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
									<button class="btn btn-sm btn-default col-sm-3" type="reset"> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header d-flex justify-content-between">
						<b>House List</b>
						<!-- Show Archived Button -->
						<button class="btn btn-sm btn-secondary" id="toggleArchive">
							Show Archived
						</button>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">House</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="houseList">
								<?php
								$i = 1;
								$archiveCondition = isset($_GET['show_archived']) && $_GET['show_archived'] == '1' ? "" : "and h.archive ='0'";
								$house = $conn->query("SELECT h.*, c.name as cname, 
                      (SELECT COUNT(*) FROM tenants WHERE house_id = h.id) as tenant_count 
                      FROM houses h 
                      INNER JOIN categories c ON c.id = h.category_id 
                      $archiveCondition 
                      ORDER BY id ASC");

while ($row = $house->fetch_assoc()):
    $rowClass = $row['archive'] == 1 ? 'archived' : '';
    $disableArchive = $row['tenant_count'] > 0 ? 'disabled' : '';
?>
<tr class="<?php echo $rowClass; ?>">
    <td class="text-center"><?php echo $i++ ?></td>
    <td class="">
        <p>House #: <b><?php echo $row['house_no'] ?></b></p>
        <p><small>House Type: <b><?php echo $row['cname'] ?></b></small></p>
        <p><small>Description: <b><?php echo $row['description'] ?></b></small></p>
        <p><small>Price: <b><?php echo number_format($row['price'], 2) ?></b></small></p>
    </td>
	<td class="text-center">
    <?php if ($row['archive'] == 1): ?>
        <!-- Disable Edit Button for Archived Houses -->
        <button class="btn btn-sm btn-primary edit_house" type="button" data-id="<?php echo $row['id']; ?>" disabled>Edit</button>
        <button class="btn btn-sm btn-success toggle_archive" type="button" data-id="<?php echo $row['id']; ?>" data-action="unarchive">Unarchive</button>
    <?php else: ?>
        <!-- Enable Edit Button for Active Houses -->
        <button class="btn btn-sm btn-primary edit_house" type="button" data-id="<?php echo $row['id']; ?>" data-house_no="<?php echo $row['house_no']; ?>" data-description="<?php echo $row['description']; ?>" data-category_id="<?php echo $row['category_id']; ?>" data-price="<?php echo $row['price']; ?>">Edit</button>
        <button class="btn btn-sm btn-danger toggle_archive" type="button" data-id="<?php echo $row['id']; ?>" data-action="archive">Archive</button>
    <?php endif; ?>
</td>

</tr>
<?php endwhile; ?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<style>
				.archived {
					background-color: gray !important;
					color: white;
				}

				.archived td,
				.archived p,
				.archived small {
					color: white !important;
				}
			</style>
			<script>
				// Toggle Archive View
				document.getElementById('toggleArchive').addEventListener('click', function() {
					const urlParams = new URLSearchParams(window.location.search);
					if (urlParams.has('show_archived') && urlParams.get('show_archived') === '1') {
						urlParams.delete('show_archived');
					} else {
						urlParams.set('show_archived', '1');
					}
					window.location.search = urlParams.toString();
				});

				// Archive Action (AJAX Handler)
				document.querySelectorAll('.toggle_archive').forEach(button => {
    button.addEventListener('click', function () {
        const houseId = this.dataset.id;
        const action = this.dataset.action;

        // Confirmation prompt
        const actionText = action === 'archive' ? 'archive this house?' : 'unarchive this house?';
        if (!confirm(`Are you sure you want to ${actionText}`)) return;

        // Send the request to the backend
        fetch('archive_house.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ id: houseId, action: action })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`House successfully ${action === 'archive' ? 'archived' : 'unarchived'}!`);
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred while updating the house status.');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert('An error occurred. Please try again.');
            });
    });
});

			</script>
			<!-- Table Panel -->
		</div>
	</div>

</div>

<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset;
		padding: unset;
		line-height: 1em;
	}
</style>
<script>
	$('#manage-house').on('reset', function(e) {
		$('#msg').html('')
	})
	$('#manage-house').submit(function(e) {
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url: 'ajax.php?action=save_house',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully saved", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				} else if (resp == 2) {
					$('#msg').html('<div class="alert alert-danger">House number already exist.</div>')
					end_load()
				}
			}
		})
	})
	$('.edit_house').click(function() {
		start_load()
		var cat = $('#manage-house')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='house_no']").val($(this).attr('data-house_no'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		cat.find("[name='price']").val($(this).attr('data-price'))
		cat.find("[name='category_id']").val($(this).attr('data-category_id'))
		end_load()
	})
	$('.delete_house').click(function() {
		_conf("Are you sure to delete this house?", "delete_house", [$(this).attr('data-id')])
	})

	function delete_house($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_house',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>