<?php
// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input values
    $firstName = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $lastName = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email_input'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));


    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($email)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all required fields.'
        ]);
        exit;
    }

    // Email setup
    $to = "contact@bluepeaklending.net"; // 👈 Replace this with your real email address
    $subject = "Contact details from $firstName $lastName";

    // Email body
    $body = "
        A contact form has been submitted:

        -------------------------------
        PERSONAL INFORMATION
        -------------------------------
        Full Name: $firstName $lastName
        Email: $email
        Phone: $phone

        -------------------------------
    ";

    // Email headers
    $headers = "From: noreply@yourdomain.com\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Try to send email
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'success' => true,
            'message' => 'Your loan application was submitted successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'There was an error sending your application. Please try again later.'
        ]);
    }
} else {
    // Invalid request
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>
