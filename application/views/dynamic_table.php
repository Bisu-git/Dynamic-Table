<!DOCTYPE html>
<html>
<head>
    <title>Fetch Table Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body.modal-open {
            overflow: auto !important;
        }


        .table thead th {
        background-color: #e9ecef;
        }

        /* body {
        background: linear-gradient(to right, #4facfe, #00f2fe);
        min-height: 100vh;
        } */

    </style>

</head>
<body>

    <div id="dynamic_loader"
        style="position: fixed;width: 100%;height: 100%;display: flex;align-items: center;justify-content: center;background: #ffffffc9;z-index: 9999;">
        <img src="<?php echo base_url(); ?>uploads/Loaders/Animation_loader.gif" alt="Loading...">
    </div>

<?php include APPPATH . 'views/include/header.php'; ?>


<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Fetch Table Data</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addGpModal">Add Dynamic Details</button>
    </div>

    <div class="row g-3 align-items-end mb-4">
        <div class="col-md-6">
            <label for="table_selector" class="form-label">Select Table</label>
            <select class="form-select" id="table_selector">
                <option value="">-- Select Table --</option>
                <?php foreach($tables as $table): ?>
                    <option value="<?= $table ?>"><?= $table ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button id="fetch_table" class="btn btn-primary w-100">Fetch</button>
        </div>
    </div>

    <div id="table_result" class="table-responsive"></div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form id="editForm">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Row</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-body-fields">
                    <!-- Fields will be added dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addGpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form id="addForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="add-modal-body-fields">
                    <!-- Input fields will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>

</div>

<?php include APPPATH . 'views/include/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
let currentTable = '';

// Fetch table
$('#fetch_table').click(function () {
    const tableName = $('#table_selector').val();
    if (!tableName) return alert("Please select a table.");

    currentTable = tableName;
     $('#dynamic_loader').fadeIn(100);

    $.post('<?php echo site_url('Dynamic_table/fetch_table_data'); ?>', { table: tableName }, function (response) {
        $('#table_result').html(response);
        $('#table_result table').DataTable({ pageLength: 10 });
    }).fail(() => {
        alert("Something went wrong.");
    }).always(() => {
        $('#dynamic_loader').fadeOut(500); // Hide loader after load
    });
});

// Submit Edit
    $(document).on('click', '.edit-btn', function () {
        const rowId = $(this).data('id');
        const tableName = $(this).data('table');

        $.post('<?php echo site_url('Dynamic_table/get_row_data'); ?>', { id: rowId, table: tableName }, function (res) {
            $('#modal-body-fields').empty();
            console.log(res);

            res.columns.forEach(col => {
                if (col.Field !== 'id') {
                    const value = res.row[col.Field] || '';
                    const isCalculated = col.Comment.startsWith("Calculated");

                    $('#modal-body-fields').append(`
                        <div class="mb-3">
                            <label class="form-label">${col.Field}</label>
                            <input type="text"
                                class="form-control"
                                id="${col.Field}"
                                name="${col.Field}"
                                value="${value}"
                                ${isCalculated ? "readonly data-calculated='true' data-expression=\"" + col.Comment + "\"" : ""}
                                oninput="calculate_val()">
                        </div>
                    `);
                }
            });

            $('#editForm').attr('data-id', rowId).attr('data-table', tableName);
            new bootstrap.Modal(document.getElementById('editModal')).show();
            calculate_val(); // Recalculate on open
        }, 'json');
    });

$('#editForm').submit(function (e) {
    e.preventDefault();
    const table = $(this).attr('data-table');
    const id = $(this).attr('data-id');
    const formData = $(this).serialize() + '&id=' + id + '&table=' + table;

    $.post('<?php echo site_url('Dynamic_table/update_row'); ?>', formData, function () {
        alert("Updated Successfully");
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        $('#fetch_table').click();
    });
});


// Add button logic
$(document).on('click', '[data-bs-target="#addGpModal"]', function () {
    const selectedTable = $('#table_selector').val();
    if (!selectedTable) return alert("Please select a table first.");
    currentTable = selectedTable;

    $.post('<?php echo site_url('Dynamic_table/create_add_input_modal'); ?>', { table: currentTable }, function (res) {
        $('#add-modal-body-fields').html(res);
        new bootstrap.Modal(document.getElementById('addGpModal')).show();
    }).fail(() => alert('Failed to load modal fields.'));
});

// Submit Add
$('#addForm').submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + '&table=' + currentTable;

    $.post('<?php echo site_url('Dynamic_table/insert_row'); ?>', formData, function () {
        alert("Inserted Successfully");

        // Get modal instance and hide it
        const modalEl = document.getElementById('addGpModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        // Ensure scroll and backdrop cleanup after modal fully hides
        $(modalEl).on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open').css({
                'overflow': 'auto',
                'padding-right': ''
            });
            $('.modal-backdrop').remove();
            $(modalEl).off('hidden.bs.modal'); // prevent duplicate binding
        });

        // Refresh data table or view
        $('#fetch_table').click();
    });
});

$('#addModal').on('hidden.bs.modal', function () {
    $('body').removeClass('modal-open');
    $('body').css('overflow', 'auto');
});

$(document).ready(function () {
    $('#dynamic_loader').fadeOut(500);
});

// function calculate_val(){
//     let basic = document.getElementById("Basic_Salary").value;
//     let medical = document.getElementById("Madical_Allowance").value;
//     let house = document.getElementById("House_Rent").value;
//     let Conveyance = document.getElementById("Conveyance_Allowance").value;
//     let Insurance = document.getElementById("INSURANCE").value;
//     let Gratuity = document.getElementById("GRATUITY").value;
//     let Tax = document.getElementById("Tax").value;
//     let total = document.getElementById("total");

//     let sum = basic + medical + house + conveyance + insurance ;
//     document.getElementById("total").value = sum.toFixed(2);

//      let sum = basic + medical + house + conveyance + insurance ;
//     document.getElementById("total").value = sum.toFixed(2);
//     let takeHome = grandTotal - gratuity;
//     document.getElementById("TAKE_HOME_SALARY").value = takeHome.toFixed(2);
// }


    function calculate_val() {
        const inputs = document.querySelectorAll("input[data-calculated]");
        inputs.forEach(input => {
            let expression = input.getAttribute("data-expression");

            if (expression.startsWith("Calculated")) {
                let rawExpr = expression.match(/\((.*?)\)/);
                if (!rawExpr) return;

                let expr = rawExpr[1];

                // Replace 'Field' with current input values
                let evaluatedExpr = expr.replace(/'([^']+)'/g, (_, fieldName) => {
                    let fieldInput = document.getElementById(fieldName);
                    return fieldInput ? parseFloat(fieldInput.value) || 0 : 0;
                });

                try {
                    let result = eval(evaluatedExpr);
                    input.value = result.toFixed(2);
                } catch {
                    input.value = 'Error';
                }
            }
        });
    }


    function calculate_values() {
        // Get all inputs
        const inputs = document.querySelectorAll("input[data-calculated]");
        
        inputs.forEach(input => {
            let expression = input.getAttribute("data-expression");

            if (expression.startsWith("Calculated")) {
                // Extract the part inside Calculated (...)
                let rawExpr = expression.match(/\((.*?)\)/);
                if (!rawExpr) return;
                
                let expr = rawExpr[1];

                // Replace field names with their current values
                let evaluatedExpr = expr.replace(/'([^']+)'/g, (match, fieldName) => {
                    let fieldInput = document.getElementById(fieldName);
                    if (fieldInput) {
                        let val = parseFloat(fieldInput.value) || 0;
                        return val;
                    }
                    return 0;
                });

                // Evaluate final value
                try {
                    let result = eval(evaluatedExpr);
                    input.value = result.toFixed(2);
                } catch (err) {
                    input.value = 'Error';
                    console.error("Invalid expression:", evaluatedExpr);
                }
            }
        });
    }


    
    $(document).on('click', '.delete-btn', function () {
        const rowId = $(this).data('id');
        const tableName = $(this).data('table');

        if (!confirm("Are you sure you want to delete this row?")) return;

        $.post('<?php echo site_url('Dynamic_table/del_row_data'); ?>', 
            { id: rowId, table: tableName }, 
            function (res) {
                if (res.trim() === 'success') {
                    alert('Row deleted successfully.');
                    $('#fetch_table').click(); // Refresh table
                } else {
                    alert('Delete failed.');
                }
            });
    });


</script>


</body>
</html>
