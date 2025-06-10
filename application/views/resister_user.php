<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      min-height: 100vh;
    }
    .register-container {
      max-width: 600px;
      margin: auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
<?php include APPPATH . 'views/include/header.php'; ?>

<div class="container py-5">
  <div class="register-container">
    <h3 class="text-center mb-4">Register</h3>
        <?php if ($this->session->flashdata('messages')): ?>
            <div class="alert alert-info alert-dismissible fade show mx-5 mt-3" role="alert">
                <?php echo $this->session->flashdata('messages'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <form action="<?php echo site_url("Login/registerUser"); ?>" method="POST">
      <div class="row mb-3">
        <div class="col">
          <label for="firstName" class="form-label">First Name</label>
          <input type="text" name="firstName" class="form-control" required>
        </div>
        <div class="col">
          <label for="lastName" class="form-label">Last Name</label>
          <input type="text" name="lastName" class="form-control" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="emailId" class="form-label">Email</label>
        <input type="email" name="emailId" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="mobileNumber" class="form-label">Mobile Number</label>
        <input type="text" name="mobileNumber" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="userPassword" class="form-label">Password</label>
        <input type="password" name="userPassword" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <input type="password" name="confirmPassword" class="form-control" required>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary px-4">Submit</button>
        <a href="<?php echo site_url("Login/register_user"); ?>" class="btn btn-secondary px-4">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include APPPATH . 'views/include/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
