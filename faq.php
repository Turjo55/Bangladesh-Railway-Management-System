<?php 
// faq.php - Finalized code for the Frequently Asked Questions page
// Ensure all necessary dependencies (config, header) are included.
$pageTitle = "FAQ";

// Basic safety check for included files
if (file_exists('includes/config.php')) {
    include 'includes/config.php'; 
} else {
    // Fallback if config is missing (highly unlikely based on project context)
    die("Error: Configuration file is missing.");
}

// Basic safety check for included files
if (file_exists('includes/header.php')) {
    include 'includes/header.php'; 
} else {
    die("Error: Header file is missing.");
}
?>

<div class="container my-5">
    <h1 class="text-center mb-5 text-primary"><i class="fas fa-list-alt me-2"></i> Frequently Asked Questions</h1>
    
    <div class="accordion" id="faqAccordion">

        <h2 class="mt-4 mb-3 text-secondary">Ticket Booking Process</h2>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingBook1">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBook1" aria-expanded="false" aria-controls="collapseBook1">
                    1. How do I search for available trains?
                </button>
                <p class="text-muted small px-3 mb-0 pt-1">Learn the quick steps for finding trains on the home page.</p>
            </h2>
            <div id="collapseBook1" class="accordion-collapse collapse" aria-labelledby="headingBook1" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-primary">
                    Enter **Origin**, **Destination**, and **Journey Date** on the Home page search form.
                    Click **'Find Trains'** to view all scheduled trains and available seats.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingBook2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBook2" aria-expanded="false" aria-controls="collapseBook2">
                    2. Can I book tickets without registering?
                </button>
                <p class="text-muted small px-3 mb-0 pt-1">Understand the security requirements for making a booking.</p>
            </h2>
            <div id="collapseBook2" class="accordion-collapse collapse" aria-labelledby="headingBook2" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-primary">
                    No, a **registered account** is required for user verification and security.
                    Please log in or register before proceeding to final payment.
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingBook3">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBook3" aria-expanded="false" aria-controls="collapseBook3">
                    3. What payment methods are accepted?
                </button>
                <p class="text-muted small px-3 mb-0 pt-1">View the full list of accepted cards and mobile payment options.</p>
            </h2>
            <div id="collapseBook3" class="accordion-collapse collapse" aria-labelledby="headingBook3" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-primary">
                    We accept **Credit/Debit Cards** (VISA, MasterCard) and **Mobile Financial Services (MFS)**.
                    Major MFS options include bKash and Nagad.
                </div>
            </div>
        </div>
        
        <h2 class="mt-5 mb-3 text-secondary">Cancellation Policy</h2>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCancel1">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCancel1" aria-expanded="false" aria-controls="collapseCancel1">
                    1. What is the deadline to cancel a reserved ticket?
                </button>
                <p class="text-muted small px-3 mb-0 pt-1">The maximum time before departure you can cancel.</p>
            </h2>
            <div id="collapseCancel1" class="accordion-collapse collapse" aria-labelledby="headingCancel1" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-primary">
                    Tickets must be cancelled at least **48 hours** before the train's scheduled departure time.
                    Cancellations made after this deadline are not eligible for a refund.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCancel2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCancel2" aria-expanded="false" aria-controls="collapseCancel2">
                    2. Is there a fee for cancelling a ticket?
                </button>
                <p class="text-muted small px-3 mb-0 pt-1">Information regarding cancellation charges and fees.</p>
            </h2>
            <div id="collapseCancel2" class="accordion-collapse collapse" aria-labelledby="headingCancel2" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-primary">
                    Yes, a standard **service fee** will be deducted from the total fare.
                    The fee depends on the class of travel and time before departure.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCancel3">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCancel3" aria-expanded="false" aria-controls="collapseCancel3">
                    3. How long does it take to receive the refund?
                </button>
                <p class="text-muted small px-3 mb-0 pt-1">Estimated timeline for refund processing.</p>
            </h2>
            <div id="collapseCancel3" class="accordion-collapse collapse" aria-labelledby="headingCancel3" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-primary">
                    Refunds are typically processed within **5-7 working days** after a successful cancellation.
                    The exact time depends on your bank or payment gateway.
                </div>
            </div>
        </div>
        
    </div>
</div>

<?php 
// Check the footer file to ensure the Bootstrap JavaScript is included. 
// If the answers do not toggle (show/hide), the JavaScript is missing in includes/footer.php.
include 'includes/footer.php'; 
?>