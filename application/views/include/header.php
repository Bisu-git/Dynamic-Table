<div class="menu">
  <h2>GP Management Portal</h2>
  <nav>
    <a href="<?php echo site_url('Home'); ?>">Home</a>
    <a href="<?php echo site_url('Create_schema'); ?>">Create Table</a>
    <a href="<?php echo site_url('Edit_schema'); ?>">Edit Table</a>
    <a href="<?php echo site_url('Home/csv_view'); ?>">Csv to Sql</a>
    <a href="<?php echo site_url('Dynamic_table'); ?>">Dynamic Table</a>
    <a class="d-none" href="<?php echo site_url('Home/dynamics_input'); ?>">Dynamic Details</a>
    <a href="<?php echo site_url('Login/register_user'); ?>">Register Details</a>
    <a href="<?php echo site_url('Login/logout'); ?>">Log Out</a>
  </nav>
</div>

<style>
  /* .menu {
    background-color: #343a40;
    color: white;
    padding: 15px;
  }

  .menu h2 {
    margin: 0 0 10px 0;
  }

  .menu nav a {
    color: white;
    margin-right: 15px;
    text-decoration: none;
  }

  .menu nav a:hover {
    text-decoration: underline;
  } */


  .menu {
      background-color: #343a40;
      padding: 15px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .menu nav a {
      color: white;
      margin-left: 15px;
      text-decoration: none;
    }
</style>