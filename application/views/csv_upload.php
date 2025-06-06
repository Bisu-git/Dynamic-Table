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
            <h4 class="mb-0">CSV to MySQL Table</h4>
                <?php if ($this->session->flashdata('message')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('message'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
        </div>
        <div class="card-body">
            

            <form action="<?php echo site_url("Home/uploadCsv"); ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">CSV FILE:</label>
                    <input type="file" name="csv_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Upload</button>
            </form>

        </div>
    </div>
</div>

<?php include APPPATH . 'views/include/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
