<?php 
// support.php
$pageTitle = "Support Center";
include 'includes/config.php'; 
include 'includes/header.php'; 
?>

<div class="container my-5">
    <h1 class="text-center mb-5 text-primary"><i class="fas fa-headset me-2"></i> Support Center</h1>
    
    <p class="lead text-center mb-5">We are here to help you with your journey. Choose an option below:</p>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card text-center p-4 h-100 shadow-sm">
                <i class="fas fa-question-circle fa-4x text-info mb-3"></i>
                <h3 class="card-title">Frequently Asked Questions (FAQ)</h3>
                <p class="card-text text-muted">Find quick answers to common questions about booking, cancellation, and refunds.</p>
                <a href="faq.php" class="btn btn-info btn-lg mt-3">Go to FAQ</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center p-4 h-100 shadow-sm">
                <i class="fas fa-envelope fa-4x text-success mb-3"></i>
                <h3 class="card-title">Contact Us Directly</h3>
                <p class="card-text text-muted">Need personalized assistance? Send us a message or find our office details.</p>
                <a href="contact.php" class="btn btn-success btn-lg mt-3">Contact Team</a>
            </div>
        </div>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>