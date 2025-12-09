<?php 
// filepath: d:\Xampp and all\XAMPP\htdocs\Bangladesh Railway Management System\login.php
$pageTitle = "Login/Signup";
if (session_status() === PHP_SESSION_NONE) session_start();
include 'includes/header.php'; 
require_once 'includes/config.php'; // Ensure MongoDB config is loaded

$loginError = '';

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginId'])) {
    $loginId = trim($_POST['loginId']);
    $loginPassword = trim($_POST['loginPassword']);
    
    $db = getMongoDB();
    $usersCollection = $db->users;

    // Find user by email or phone
    $user = $usersCollection->findOne([
        '$or' => [
            ['email' => $loginId],
            ['phone' => $loginId]
        ]
    ]);

    if ($user) {
        // Verify password (assuming hashed, but for now matching plain text if that was the legacy or verify hash)
        // Check if password_verify should be used. The legacy code used plain text 'password123'. 
        // We will try both: plain text (transition) or verify.
        // For new system, we should assume password_verify.
        
        $passwordValid = false;
        if (password_verify($loginPassword, $user['password'])) {
            $passwordValid = true;
        } elseif ($user['password'] === $loginPassword) { 
             // Temporary fallback for legacy helper users or if hash migration isn't done
             $passwordValid = true;
        }

        if ($passwordValid) {
            $_SESSION['user_id'] = (string)$user['_id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name']; // Useful for display

            if ($user['role'] === 'Super Admin') {
                header("Location: admin/train_route_mgr.php");
            } else {
                header("Location: passenger_dashboard.php");
            }
            exit;
        } else {
            $loginError = "Invalid password.";
        }
    } else {
         // Fallback for hardcoded admin if not in DB yet (Development safety)
          if ($loginId == 'admin@br.gov.bd' && $loginPassword == 'admin123') {
            $_SESSION['user_id'] = 'admin_legacy'; 
            $_SESSION['user_role'] = 'Super Admin';
            header("Location: admin/train_route_mgr.php");
            exit;
        }
        $loginError = "User not found. Please register.";
    }
}
?>

<section class="my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <!-- LOGIN CARD -->
            <div class="card p-4 mb-4 shadow-sm">
                <h3 class="card-title text-center text-primary mb-4">Passenger Login</h3>
                
                <?php if (!empty($loginError)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($loginError); ?>
                    </div>
                <?php endif; ?>
                
                <form action="login.php" method="POST">
                    <!-- Email/Phone Field -->
                    <div class="mb-3">
                        <label for="loginId" class="form-label fw-bold">Email or Phone Number</label>
                        <input type="text" id="loginId" name="loginId" class="form-control" 
                               placeholder="rahim@example.com" value="rahim@example.com" required />
                    </div>
                    
                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label fw-bold">Password</label>
                        <input type="password" id="loginPassword" name="loginPassword" class="form-control" 
                               placeholder="Enter your password" value="password123" required />
                    </div>
                    
                    <!-- Forgot Password & Remember Me -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="#!" class="text-muted small">Forgot Password?</a>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" checked />
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                    </div>
                    
                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary w-100 mb-4 fw-bold">
                        <i class="fas fa-sign-in-alt me-2"></i>Log In
                    </button>
                    
                    <!-- Register Link -->
                    <p class="text-center small">Not a member? <a href="#signupForm" data-bs-toggle="collapse" class="text-primary fw-bold">Register Now</a></p>
                </form>
            </div>
            
            <!-- REGISTER CARD (Collapsed) -->
            <div class="collapse" id="signupForm">
                <div class="card p-4 shadow-sm">
                    <h3 class="card-title text-center text-secondary mb-4">Create Account</h3>
                    <form method="POST" action="register_action.php">
                        <div class="mb-3">
                            <label for="regName" class="form-label fw-bold">Full Name</label>
                            <input type="text" id="regName" name="name" class="form-control" placeholder="Your full name" required />
                        </div>
                        <div class="mb-3">
                            <label for="regEmail" class="form-label fw-bold">Email</label>
                            <input type="email" id="regEmail" name="email" class="form-control" placeholder="your@email.com" required />
                        </div>
                        <div class="mb-3">
                            <label for="regPhone" class="form-label fw-bold">Phone</label>
                            <input type="tel" id="regPhone" name="phone" class="form-control" placeholder="01XXXXXXXXX" />
                        </div>
                        <div class="mb-3">
                            <label for="regPassword" class="form-label fw-bold">Password</label>
                            <input type="password" id="regPassword" name="password" class="form-control" placeholder="Min. 6 characters" required />
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            <i class="fas fa-user-plus me-2"></i>Sign Up
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Ensure labels are always visible and above inputs */
.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: #333;
    font-size: 0.95rem;
}

.form-control {
    border-radius: 6px;
    border: 1px solid #ddd;
    padding: 0.75rem;
}

.form-control:focus {
    border-color: #2c3e50;
    box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.15);
}
</style>

<?php 
include 'includes/footer.php'; 
?>