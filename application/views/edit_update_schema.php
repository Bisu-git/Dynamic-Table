<!DOCTYPE html>
<html>
<head>
    <title>Edit Table Schema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include APPPATH . 'views/include/header.php'; ?>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Modify MySQL Table Schema</h4>
                    <?php if ($this->session->flashdata('message')): ?>
                        <div class="alert alert-info alert-dismissible fade show mx-5 mt-3" role="alert">
                            <?php echo $this->session->flashdata('message'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
        </div>
        <div class="card-body">
            <form id="schemaForm" method="post" action="<?= site_url("Edit_schema/processAction") ?>">
                <div class="mb-3">
                    <label class="form-label">Select Table:</label>
                    <select id="all_tables" name="table_name" class="form-select" required>
                        <option value="">-- Select Table --</option>
                        <?php foreach ($all_table as $tables): ?>
                            <option value="<?= $tables ?>"><?= $tables ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Action:</label>
                    <select id="action_type" name="action_type" class="form-select" required>
                        <option value="">-- Select Action --</option>
                        <option value="add">ADD</option>
                        <option value="edit">EDIT</option>
                        <option value="delete">DELETE</option>
                    </select>
                </div>

                <div id="dynamic_section"></div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include APPPATH . 'views/include/footer.php'; ?>

<script>
$(document).ready(function() {
    $('#action_type, #all_tables').on('change', function() {
        let table = $('#all_tables').val();
        let action = $('#action_type').val();
        $('#dynamic_section').empty();

        if (table && action) {
            $.ajax({
                url: "<?= site_url('Edit_schema/get_columns') ?>",
                type: "POST",
                data: { table_name: table },
                success: function(response) {
                    let columns = JSON.parse(response);
                    let html = '';

                    if (action === 'edit') {
                        html += `<div class="mb-3">
                            <label>Select Column to Edit:</label>
                            <select name="column_name" class="form-select">`;
                        columns.forEach(col => {
                            html += `<option value="${col.Field}">${col.Field}</option>`;
                        });
                        html += `</select></div>`;

                        html += `
                            <div class="mb-3">
                                <label>New Column Name:</label>
                                <input type="text" name="new_column_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>New Data Type:</label>
                                <select name="new_column_type" class="form-select">
                                    <option value="VARCHAR(255)">VARCHAR</option>
                                    <option value="INT">INT</option>
                                    <option value="TEXT">TEXT</option>
                                    <option value="DATE">DATE</option>
                                    <option value="DATETIME">DATETIME</option>
                                    <option value="FLOAT">FLOAT</option>
                                </select>
                            </div>`;
                    } else if (action === 'delete') {
                        html += `<div class="mb-3">
                            <label>Select Column to Delete:</label>
                            <select name="column_name" class="form-select">`;
                        columns.forEach(col => {
                            html += `<option value="${col.Field}">${col.Field}</option>`;
                        });
                        html += `</select></div>`;
                    } else if (action === 'add') {
                        html += `<div class="mb-3">
                            <label>Select Column to Insert After:</label>
                            <select name="after_column" class="form-select">`;
                        columns.forEach(col => {
                            html += `<option value="${col.Field}">${col.Field}</option>`;
                        });
                        html += `</select></div>`;

                        html += `
                            <div class="mb-3">
                                <label>New Column Name:</label>
                                <input type="text" name="new_column_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Data Type:</label>
                                <select name="new_column_type" class="form-select">
                                    <option value="VARCHAR(255)">VARCHAR</option>
                                    <option value="INT">INT</option>
                                    <option value="TEXT">TEXT</option>
                                    <option value="DATE">DATE</option>
                                    <option value="DATETIME">DATETIME</option>
                                    <option value="FLOAT">FLOAT</option>
                                </select>
                            </div>`;
                    }

                    $('#dynamic_section').html(html);
                }
            });
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
