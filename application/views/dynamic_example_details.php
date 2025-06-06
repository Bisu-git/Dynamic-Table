<!DOCTYPE html>
<html>
<head>
  <title>Salary Calculation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .calc-popup {
      display: none;
      border: 1px solid #ccc;
      padding: 10px;
      background: #f9f9f9;
      position: absolute;
      z-index: 1000;
      width: 250px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .operation-list div {
      margin-top: 5px;
    }
  </style>
</head>
<body class="bg-light">

<div class="container mt-5">

  <h3 class="mb-4">Vender Salary List</h3>

  <!-- Add Button -->
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addVenderModal">➕ Add Vender</button>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Vender Name</th>
          <th>Vender ID</th>
          <th>Vender Type</th>
          <th>Basic Salary</th>
          <th>Medical Allowance</th>
          <th>House Rent</th>
          <th>Conveyance Allowance</th>
          <th>Tax</th>
          <th>Insurance</th>
          <th>Gratuity</th>
          <th>Total</th>
          <th>Grand Total</th>
          <th>Take Home</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($vender_salary as $v): ?>
          <tr>
            <td><?= $v->id ?></td>
            <td><?= $v->Vender_name ?></td>
            <td><?= $v->vender_id ?></td>
            <td><?= $v->Vender_type ?></td>
            <td><?= $v->Basic_Salary ?></td>
            <td><?= $v->Madical_Allowance ?></td>
            <td><?= $v->House_Rent ?></td>
            <td><?= $v->Conveyance_Allowance ?></td>
            <td><?= $v->Tax ?></td>
            <td><?= $v->INSURANCE ?></td>
            <td><?= $v->GRATUITY ?></td>
            <td><?= $v->total ?></td>
            <td><?= $v->Grand_total ?></td>
            <td><?= $v->TAKE_HOME_SALARY ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addVenderModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Vender Salary</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="add-modal-body-fields">

        <label class="form-label">Vender Name</label>
        <input type="text" id="vender_name" placeholder="Vender Name" class="form-control mb-2" />

        <label class="form-label">Vender ID</label>
        <input type="text" id="vender_id" placeholder="Vender ID" class="form-control mb-2" />

        <label class="form-label">Vender type</label>
        <input type="text" id="vender_type" placeholder="Vender type" class="form-control mb-2" />

        <label class="form-label">Basic Salary</label>
        <input type="text" id="Basic_Salary" placeholder="Basic Salary" class="form-control mb-2" />

        <label class="form-label">Medical Allowance</label>
        <input type="text" id="Madical_Allowance" placeholder="Medical Allowance" class="form-control mb-2" />

        <label class="form-label">House Rent</label>
        <input type="text" id="House_Rent" placeholder="House Rent" class="form-control mb-2" />

        <label class="form-label">Conveyance Allowance</label>
        <input type="text" id="Conveyance_Allowance" placeholder="Conveyance Allowance" class="form-control mb-2" />

        <label class="form-label">Insurance</label>
        <input type="text" id="INSURANCE" placeholder="Insurance" class="form-control mb-2" />

        <label class="form-label">Gratuity</label>
        <input type="text" id="GRATUITY" placeholder="Gratuity" class="form-control mb-2" />

        <label class="form-label">Tax</label>
        <input type="text" id="Tax" placeholder="Tax" class="form-control mb-2" />

        <label class="form-label">Total</label>
        <input type="text" id="total" placeholder="Total" class="form-control mb-2" />


        <!-- Grand Total Field + Calculator -->
        <div style="position: relative;">
             <label class="form-label">Grand Total</label>
          <input type="text" id="Grand_total" placeholder="Grand Total" readonly class="form-control mb-2" />
          <button type="button" id="openModalCalc" class="btn btn-sm btn-secondary" style="position: absolute; right: 10px; top: 35px;">🧮</button>
        </div>

        <!-- Calculator Popup -->
        <div class="calc-popup" id="modal-calc-popup">
          <div>
            <label>Select Operation:</label><br>
            <select class="form-select mb-1 operand">
              <option value="+">+</option>
              <option value="-">-</option>
              <option value="*">*</option>
              <option value="/">/</option>
            </select>
           <select class="form-select mb-2 column">
                <?php foreach ($manual_columns as $col): ?>
                    <option value="<?= $col->COLUMN_NAME ?>">
                        <?= ucwords(str_replace('_', ' ', $col->COLUMN_NAME)) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="btn btn-sm btn-outline-success add-line">➕ Add</button>
          </div>
          <div class="operation-list my-2"></div>
          <button class="btn btn-primary btn-sm calculate">Calculate</button>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS (for modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){

  // Toggle calculator popup
  $("#openModalCalc").click(function(){
    $("#modal-calc-popup").toggle();
  });

  // Add operation line
  $(".add-line").click(function(){
    let popup = $("#modal-calc-popup");
    let operand = popup.find(".operand").val();
    let column = popup.find(".column").val();
    // console.log(column);
    let columnText = popup.find(".column option:selected").text();

    popup.find(".operation-list").append(`
      <div data-op="${operand}" data-col="${column}">
        ${operand} ${columnText}
        <button class="btn btn-sm btn-danger btn-close remove" aria-label="Close"></button>
      </div>
    `);
  });

  // Remove line
  $(document).on("click", ".remove", function(){
    $(this).parent().remove();
  });

  // Calculate and update Grand_total in modal
  $(".calculate").click(function(){
    let popup = $("#modal-calc-popup");
    let result = 0;

    popup.find(".operation-list div").each(function(i){
      let op = $(this).data("op");
      let col = $(this).data("col");
      let val = parseFloat($("#" + col).val()) || 0;

      if(i === 0) {
        result = val;
      } else {
        if(op === "+") result += val;
        else if(op === "-") result -= val;
        else if(op === "*") result *= val;
        else if(op === "/") result /= val;
      }
    });

    $("#Grand_total").val(result.toFixed(2));
    popup.hide();
  });

});
</script>

</body>
</html>
