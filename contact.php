<?php 
// contact.php
$pageTitle = "Contact Us";
include 'includes/config.php'; 
include 'includes/header.php'; 
?>

<div class="container my-5">
    <h1 class="text-center mb-5 text-primary"><i class="fas fa-phone-alt me-2"></i> Get In Touch</h1>
    
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card p-4 h-100 shadow-sm">
                <h3 class="mb-3 text-secondary">Send Us a Message</h3>
                <form action="contact_submit.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Inquiry</button>
                </form>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card p-4 h-100 shadow-sm">
                <h3 class="mb-3 text-secondary">Contact Information</h3>
                <p><strong>Head Office:</strong></p>
                <p>Rail Bhaban, 17/A, Dhaka 1000, Bangladesh</p>
                <hr>
                <p><strong>Customer Service Hotline:</strong></p>
                <p><i class="fas fa-phone me-2"></i> +880-XXX-XXXXXX</p>
                <hr>
                <p><strong>Email Support:</strong></p>
                <p><i class="fas fa-at me-2"></i> support@railwayticketing.bd</p>
            </div>
        </div>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>