<!DOCTYPE html>
<html>
<head>
    <title>Create Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include APPPATH . 'views/include/header.php'; ?>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Create MySQL Table</h4>
            <?php if ($this->session->flashdata('message')): ?>
                <div class="alert alert-info alert-dismissible fade show mx-5 mt-3" role="alert">
                    <?php echo $this->session->flashdata('message'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <form action="<?= site_url("Create_schema/createTable") ?>" method="post" id="schemaForm">
                <div class="mb-3">
                    <label class="form-label">Table Name:</label>
                    <input type="text" name="table_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Number of Columns:</label>
                    <select id="column_count" name="column_count" class="form-select">
                        <?php for($i = 1; $i <= 50; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div id="columns_container"></div>

                <button type="submit" class="btn btn-success mt-3 w-100">Create Table</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal for calculated expression -->
<div class="modal fade" id="calcModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content p-3">
        <div>
            <label>Operation:</label>
            <select class="form-select mb-2" id="operator">
                <option value="+">+</option>
                <option value="-">-</option>
                <option value="*">*</option>
                <option value="/">/</option>
            </select>

            <label>Column:</label>
            <select class="form-select mb-2" id="fieldSelector">
                <!-- dynamic -->
            </select>

            <!-- <label>Instructions: <div> ('Basic' * (1 + 'Gst' / 100) ) <div></label> -->
            <label>
                % Formula:
                <span style="color: red;">
                    ('Basic' * (1 + 'Gst' / 100))
                </span>
            </label>

            <input type="text" id="formula_dynamic" name="formula_dynamic" class="">

            <button type="button" class="btn btn-sm btn-outline-success mb-2" id="addLine">➕ Add</button>
            <div id="expressionPreview" class="text-primary mb-2 fw-bold small"></div>
            <button type="button" class="btn btn-sm btn-primary" id="finalizeCalc">Done</button>
        </div>
    </div>
  </div>
</div>

<?php include APPPATH . 'views/include/footer.php'; ?>
<script>
let currentCommentIndex = null;
let expressionParts = [];

$(document).ready(function () {
    $('#column_count').on('change', function () {
        const count = $(this).val();
        const container = $('#columns_container');
        container.empty();

        for (let i = 0; i < count; i++) {
            container.append(`
                <div class="row mb-2 align-items-end column-block" data-index="${i}">
                    <div class="col-md-4">
                        <label>Column ${i+1} Name:</label>
                        <input type="text" name="columns[${i}][name]" class="form-control column-name" required>
                    </div>
                    <div class="col-md-4">
                        <label>Data Type:</label>
                        <select name="columns[${i}][type]" class="form-select">
                            <option value="VARCHAR(255)">VARCHAR</option>
                            <option value="INT">INT</option>
                            <option value="FLOAT">FLOAT</option>
                            <option value="DATE">DATE</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Comment:</label>
                        <select name="columns[${i}][comment]" class="form-select comment-select" data-index="${i}">
                            <option value="">None</option>
                            <option value="Manual">Manual</option>
                            <option value="Calculated">Calculated</option>
                        </select>
                        <input type="hidden" class="calc-comment" name="columns[${i}][comment]" value="">
                    </div>
                </div>
            `);
        }
    }).trigger('change');

    // Handle calculated field comment selection
    $(document).on('change', '.comment-select', function () {
        const val = $(this).val();
        const index = $(this).data('index');
        const commentField = $(`.column-block[data-index="${index}"] .calc-comment`);

        if (val === 'Calculated') {
            currentCommentIndex = index;
            expressionParts = [];

            // Populate modal dropdown with all existing column names
            const fields = [];
            $('.column-name').each(function () {
                const colName = $(this).val();
                if (colName) fields.push(colName);
            });

            $('#fieldSelector').empty();
            $('#fieldSelector').append(`<option value="">None</option>`);
            fields.forEach(f => {
                const value = f.replace(/\s+/g, '_'); // Safe for expressions
                const label = f.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()); // User-friendly
                $('#fieldSelector').append(`<option value="${value}">${label}</option>`);
            });

            $('#expressionPreview').text('');
            $('#calcModal').modal('show');
        } else {
            // ✅ Fix: update the comment hidden field for Manual or None
            commentField.val(val);
        }
    });


            // Add expression part
        let expressionParts = [];
        // Add expression part
        $('#addLine').click(function () {
            const operator = $('#operator').val();
            const field = $('#fieldSelector').val().trim();
            const customFormula = $('#formula_dynamic').val().trim();

            let part = '';

            if (field && customFormula) {
                // Case: both field and input value
                part = expressionParts.length === 0
                    ? `'${field}' ${operator} ${customFormula}`
                    : `${operator} '${field}' ${operator} ${customFormula}`;
            } else if (field) {
                // Case: only column selected
                part = expressionParts.length === 0
                    ? `'${field}'`
                    : `${operator} '${field}'`;
            } else if (customFormula) {
                // Case: only value typed
                part = expressionParts.length === 0
                    ? `${customFormula}`
                    : `${operator} ${customFormula}`;
            } else {
                alert("Please select a column or enter a value.");
                return;
            }

            // Push and render
            expressionParts.push(part);
            $('#expressionPreview').append(`
                <div class="d-inline-block me-2 mb-2 part-item" data-index="${expressionParts.length - 1}">
                    ${part}
                    <button type="button" class="btn btn-sm btn-danger ms-1 remove" aria-label="Close">&times;</button>
                </div>
            `);

            // Update combined expression
            $('#hiddenExpression').val(expressionParts.join(' '));

            // Optional: clear the manual input only
            $('#formula_dynamic').val('');
            $('#fieldSelector').val('');
        });




        $(document).on("click", ".remove", function () {
            const parentDiv = $(this).closest('.part-item');
            const index = parseInt(parentDiv.data('index'));

            // Remove from array using index
            expressionParts.splice(index, 1);

            // Re-render all parts with updated indexes
            $('#expressionPreview').empty();
            expressionParts.forEach((part, i) => {
                $('#expressionPreview').append(`
                    <div class="d-inline-block me-2 mb-2 part-item" data-index="${i}">
                        ${part}
                        <button type="button" class="btn btn-sm btn-danger ms-1 remove" aria-label="Close">&times;</button>
                    </div>
                `);
            });

            // Update hidden input or preview
            $('#hiddenExpression').val(expressionParts.join(' '));
        });

    // Finalize expression
    $('#finalizeCalc').click(function () {
        if (currentCommentIndex !== null && expressionParts.length > 0) {
            const commentField = $(`.column-block[data-index="${currentCommentIndex}"] .calc-comment`);
            commentField.val(`Calculated (${expressionParts.join('')})`);
            $('#calcModal').modal('hide');
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
