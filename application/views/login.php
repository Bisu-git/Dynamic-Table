<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #667eea, #764ba2);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background-color: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
      width: 350px;
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      font-weight: 500;
    }

    .form-group input {
      width: 100%;
      padding: 10px 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      transition: border 0.3s ease;
    }

    .form-group input:focus {
      border-color: #667eea;
      outline: none;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background: #667eea;
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: #5a67d8;
    }

    /* .message {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
      color: red;
      display: none;
    } */

    .message {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>

    <!-- Flash messages from session -->
    <?php 
        if ($this->session->flashdata('success')) {
            echo '<p class="message" style="color: green;">' . $this->session->flashdata('success') . '</p>';
        } elseif ($this->session->flashdata('error')) {
            echo '<p class="message" style="color: red;">' . $this->session->flashdata('error') . '</p>';
        }
    ?>

    <form action="<?php echo site_url("Login/login"); ?>" method="post">
      <div class="form-group">
        <label for="emailid">Email</label>
        <input type="email" name="emailid" id="emailid" placeholder="Enter emailid" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter password" required>
      </div>
      <button class="btn" type="submit">Login</button>
    </form>
  </div>
</body>
</html>
