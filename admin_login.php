<?php 
// admin_login.php
// # Admin Authentication Portal
$pageTitle = "Admin Login";
include 'includes/config.php'; 
include 'includes/header.php'; 

// Simple placeholder for admin login logic (matches admin@br.gov.bd / admin123 from SQL)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adminLoginId'])) {
    
    // # Simplified login for demonstration (matches admin@br.gov.bd / admin123 from SQL)
    if ($_POST['adminLoginId'] == 'admin@br.gov.bd' && $_POST['adminPassword'] == 'admin123') {
        $_SESSION['user_id'] = 2; // Admin ID
        $_SESSION['user_role'] = 'Super Admin';
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $loginError = "Invalid Admin credentials. Use admin@br.gov.bd / admin123";
    }
}
?>

<section class="admin-dashboard-bg" style="min-height: 100vh; padding-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card p-5 admin-card-overlay">
                <h3 class="card-title text-center text-primary mb-4"><i class="fas fa-lock me-2"></i> Admin Panel Access</h3>
                <?php if (isset($loginError)): ?>
                    <div class="alert alert-danger" role="alert"><?php echo $loginError; ?></div>
                <?php endif; ?>
                <form action="admin_login.php" method="POST">
                    
                    <div class="mb-4">
                        <label class="form-label" for="adminLoginId">Admin Email</label> 
                        <input type="email" id="adminLoginId" name="adminLoginId" class="form-control" value="admin@br.gov.bd" required />
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" for="adminPassword">Password</label>
                        <input type="password" id="adminPassword" name="adminPassword" class="form-control" value="admin123" required />
                    </div>
                    
                    <div class="text-center"> 
                        <button type="submit" class="btn btn-dark mb-4">Secure Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.js"></script>
</body>
</html>