<?php
// includes/header.php
// # Improved header — copy & replace your current includes/header.php with this file.
// ...existing code...
if (session_status() === PHP_SESSION_NONE) session_start();

// base path for assets (absolute to project in htdocs)
$base = '/Bangladesh Railway Management System';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($pageTitle ?? 'Online Ticketing'); ?> - Bangladesh Railway</title>

  <!-- # Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- # Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

  <!-- # Project CSS -->
  <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/style.css">

  <!-- # Admin CSS (optional) -->
  <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/admin.css">

  <!-- # Fonts / Favicon -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="icon" href="<?php echo $base; ?>/assets/img/logo_bd_railway.png" type="image/png">
</head>
<body>
<?php
// # If this is an admin page, admin pages will include sidebar separately.
// Define IS_ADMIN in admin pages before include header: define('IS_ADMIN', true);
if (!defined('IS_ADMIN') || !IS_ADMIN):
?>
  <!-- # NAVBAR (public) -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background:var(--primary-color);">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $base; ?>/index.php">
          <img src="<?php echo $base; ?>/assets/img/logo_bd_railway.png" alt="BR Logo" height="34" style="margin-right:10px;">
          <strong class="text-white">Bangladesh Railway</strong>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
          <ul class="navbar-nav ms-auto align-items-lg-center">
            <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/search_results.php">Find Trains</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/my_bookings.php">My Bookings</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/support.php">Support</a></li>

            <?php if (!empty($_SESSION['user_id'])): ?>
              <li class="nav-item"><a class="nav-link btn btn-sm btn-outline-warning mx-2" href="<?php echo $base; ?>/logout.php">Logout</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link btn btn-sm btn-outline-warning mx-2" href="<?php echo $base; ?>/login.php">Login / Signup</a></li>
            <?php endif; ?>

            <li class="nav-item ms-2">
              <button id="darkModeToggle" class="btn btn-sm btn-link text-white" title="Toggle Dark/Light Mode">
                <i id="darkModeIcon" class="fas fa-moon"></i>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div style="height:64px;"></div>
  </header>
  <main class="mt-4 mb-5">
    <div class="container">
<?php else: ?>
  <!-- # Admin header minimal — admin pages will include sidebar -->
  <main class="admin-main">
<?php endif; ?>

<!-- # DARK MODE TOGGLE SCRIPT -->
<script>
(function(){
  const baseClass = document.documentElement;
  const key = 'br_dark_mode';
  const toggle = () => {
    const isDark = baseClass.classList.toggle('dark-mode');
    localStorage.setItem(key, isDark ? '1' : '0');
    document.getElementById('darkModeIcon').classList.toggle('fa-sun', isDark);
    document.getElementById('darkModeIcon').classList.toggle('fa-moon', !isDark);
  };
  // init
  try {
    const stored = localStorage.getItem(key);
    if (stored === '1') {
      document.documentElement.classList.add('dark-mode');
      setTimeout(()=> {
        const ic = document.getElementById('darkModeIcon'); if(ic) { ic.classList.remove('fa-moon'); ic.classList.add('fa-sun'); }
      },0);
    }
  } catch(e){}
  // attach handler after DOM loaded
  document.addEventListener('click', function(e){
    if (e.target && (e.target.id === 'darkModeToggle' || e.target.closest('#darkModeToggle'))) {
      toggle();
    }
  }, false);
})();
</script>