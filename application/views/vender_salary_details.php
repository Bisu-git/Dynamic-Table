<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vender Salary Structure</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    

    .filter-section, .container-xxl {
      background-color: #ffffff;
      margin: 20px auto;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      overflow-x: auto;
    }

    .form-group select, .form-group input {
      margin-right: 10px;
    }

    .table thead th {
      background-color: #e9ecef;
    }

    .edit-btn {
      background-color: #fd7e14;
      border: none;
      color: white;
      padding: 5px 10px;
      border-radius: 3px;
    }

    .modal .form-group {
      margin-bottom: 15px;
    }

    .form-check-inline {
      margin-right: 15px;
    }
  </style>
</head>
<body>
  <?php include APPPATH . 'views/include/header.php'; ?>


  <div class="container-xxl">
    <div class="d-flex justify-content-between mb-3">
      <h4>Vender Salary Table</h4>

      <button class="btn btn-success" data-toggle="modal" data-target="#addGpModal">Add GP Details</button>
    </div>
    <table id="gpTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>SL NO</th>
          <th>Vender</th>
          <th>Vender ID</th>
          <th>Vender Type</th>
          <th>Basic</th>
          <th>Medical</th>
          <th>House rent</th>
          <th>Conveinience Fee</th>
          <th>Tax</th>
          <th>Insurance</th>
          <th>Gratuity</th>
          <th>Total</th>
          <th>Grand Total</th>
          <th>Taken Salary</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="addGpModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <form id="addForm">
          <div class="modal-header">
            <h5 class="modal-title">Add GP Details</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-2">
                <label>Zone</label>
                <select class="form-control" name="Zone" required>
                  <option value="">Select Zone</option>
                  <option value ='1'>Zone 1</option>
                  <option value ='2'>Zone 2</option>
                  <option value ='3'>Zone 3</option>
                  <option value ='4'>Zone 4</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label>State Name</label>
                <input type="text" name="STATE_NAME" class="form-control" required>
              </div>
              <div class="form-group col-md-2">
                <label>District</label>
                <input type="text" name="District" class="form-control" required>
              </div>
              <div class="form-group col-md-2">
                <label>Block</label>
                <input type="text" name="Block" class="form-control" required>
              </div>
              <div class="form-group col-md-2">
                <label>GP Name</label>
                <input type="text" name="GP_Name" class="form-control" required>
              </div>
              <div class="form-group col-md-2">
                <label>GP Code</label>
                <input type="number" name="GP_Code" class="form-control">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2">
                <label>Latitude</label>
                <input type="text" name="Latitude" class="form-control">
              </div>
              <div class="form-group col-md-2">
                <label>Longitude</label>
                <input type="text" name="Longitude" class="form-control">
              </div>
              <div class="form-group col-md-4">
                <label>DoT Nodal Officer</label>
                <input type="text" name="DoT_Nodal_Officer_Name" class="form-control">
              </div>
              <div class="form-group col-md-4">
                <label>Contact No</label>
                <input type="text" name="DoT_Nodal_Contact_No" class="form-control">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>FTTH Connections</label>
                <input type="number" name="No_of_FTTH_Connections" class="form-control">
              </div>
              <div class="form-group col-md-4">
                <label>Avail FPO</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_FPO" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_FPO" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>
              <div class="form-group col-md-4">
                <label>Avail PACS</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PACS" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PACS" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail PanchayatBhawan</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PanchayatBhawan" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PanchayatBhawan" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail ClinicForMentallyChallenged</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_ClinicForMentallyChallenged" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_ClinicForMentallyChallenged" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_SoilTestingCenter</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_SoilTestingCenter" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_SoilTestingCenter" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_FertilizerShop</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_FertilizerShop" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_FertilizerShop" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_PoultaryDev</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PoultaryDev" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PoultaryDev" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_GoataryDev</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_GoataryDev" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_GoataryDev" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_PigeryDev</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PigeryDev" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PigeryDev" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_VeterinaryHospital</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_VeterinaryHospital" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_VeterinaryHospital" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_FishFarming</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_FishFarming" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_FishFarming" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_AquacultureExtensionFacility</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_AquacultureExtensionFacility" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_AquacultureExtensionFacility" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_Bank</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_Bank" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_Bank" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_PostOffice</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PostOffice" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PostOffice" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_PDS</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PDS" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PDS" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_PublicLibrary</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PublicLibrary" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_PublicLibrary" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

              <div class="form-group col-md-4">
                <label>Avail_CottageSmallScaleUnits</label><br>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_CottageSmallScaleUnits" value="YES" class="form-check-input">
                  <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" name="Avail_CottageSmallScaleUnits" value="NO" class="form-check-input">
                  <label class="form-check-label">No</label>
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer">
            <input type="text" name="id">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include APPPATH . 'views/include/footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#gpTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '<?php echo site_url("Home/All_GP_data"); ?>',
          type: 'POST',
          data: function (d) {
            d.column = $('#gp_details').val();
            d.value = $('#data').val();
          }
        },
        columns: [
          { data: 'sl_no' },
          { data: 'STATE_NAME' },
          { data: 'District' },
          { data: 'Block' },
          { data: 'GP_Name' },
          { data: 'GP_Code' },
          { data: 'Latitude' },
          { data: 'Longitude' },
          { data: 'DoT_Nodal_Officer_Name' },
          { data: 'DoT_Nodal_Contact_No' },
          { data: 'No_of_FTTH_Connections' },
          {
            data: null,
            render: function (data, type, row) {
              return `<button class="edit-btn" onclick="editRecord('${row.id}')">Edit</button>`;
            },
            orderable: false,
            searchable: false
          }
        ]
      });
    });

    function applyFilter() {
      $('#gpTable').DataTable().ajax.reload();
    }

    function editRecord(id) {
      $('#addGpModal').modal('show');
      alert('Edit GP: ' + id);
    }

      function editRecord(id) {
        $.ajax({
          url: '<?php echo site_url("Home/Edit_Gp_data"); ?>/' + id,
          method: 'GET',
          dataType: 'json', // Expect JSON response
          success: function(response) {
            // Populate modal form fields
            for (const key in response) {
              const value = response[key];
              const field = $('[name="' + key + '"]');

              if (field.attr("type") === "radio") {
                $('[name="' + key + '"][value="' + value + '"]').prop("checked", true);
              } else {
                field.val(value);
              }
            }

            $('#addGpModal').modal('show'); // Show modal after data loaded
          },
          error: function() {
            alert('Error fetching data!');
          }
        });
      }


    $('#addForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo site_url("Home/Add_Gp_data"); ?>',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.includes('Successfully')) {
                    $('#addGpModal').modal('hide');
                    $('#gpTable').DataTable().ajax.reload();
                    alert(response);
                } else {
                    // Show validation errors
                    $('#form-errors').html(response).show();
                }
            },
            error: function() {
                alert('Error saving data!');
            }
        });
    });

    // $(document).ready(function(){
    // 	$('#addGpModal').on('hidden.bs.modal', function(event) {
    // 		$('#addGpModal input').val('');
    // 	});
    // });

    $(document).ready(function(){
        $('#addGpModal').on('hidden.bs.modal', function() {
            // Clear text inputs, hidden inputs, and textareas
            $(this).find('input[type="text"], input[type="hidden"], input[type="number"], input[type="email"], textarea').val('');

            // Uncheck radio buttons and checkboxes
            $(this).find('input[type="radio"], input[type="checkbox"]').prop('checked', false);

            // Reset select elements
            $(this).find('select').prop('selectedIndex', 0);
        });
    });


    function applyFilter() {
      $('#gpTable').DataTable().ajax.reload();
    }
  </script>
</body>
</html>
