<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light bg-light" id="navbar">

  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
      <button id="darkModeToggle" class="btn btn-sm btn-outline-dark ml-auto"><i class="fas fa-moon"></i></button>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        
        <?= userLogin()['username'] ?> <i class="fas fa-user-cog ml-2"></i>

        </a>
        <div class="dropwdown-menu dropdown-menu dropdown-menu-right">
            <a href="<?= $main_url ?>auth/change-password.php" class="dropdown-item text-right">
                Change Password <i class="fas fa-key"></i>
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= $main_url ?>auth/logout.php" class="dropdown-item text-right">
                Logout <i class="fas fa-sign-out-alt"></i>
            </a>
          </div>
        </li>
    </ul>
  </ul>
</nav>
<!-- /.navbar -->