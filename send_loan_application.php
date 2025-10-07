<?php
// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input values
    $firstName = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $lastName = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email_input'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $loanType = htmlspecialchars(trim($_POST['loan-type'] ?? ''));
    $amount = htmlspecialchars(trim($_POST['amount'] ?? ''));
    $location = htmlspecialchars(trim($_POST['location'] ?? ''));
    $street = htmlspecialchars(trim($_POST['u_street'] ?? ''));
    $city = htmlspecialchars(trim($_POST['u_town'] ?? ''));
    $state = htmlspecialchars(trim($_POST['u_state'] ?? ''));
    $zipcode = htmlspecialchars(trim($_POST['u_zipcode'] ?? ''));
    $creditScore = htmlspecialchars(trim($_POST['credit_score'] ?? ''));
    $incomeRange = htmlspecialchars(trim($_POST['income_range'] ?? ''));
    $applicationDate = htmlspecialchars(trim($_POST['application_date'] ?? ''));
    $comments = htmlspecialchars(trim($_POST['comments'] ?? ''));

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
    $subject = "New Loan Application from $firstName $lastName";

    // Email body
    $body = "
A new loan application has been submitted:

-------------------------------
PERSONAL INFORMATION
-------------------------------
Full Name: $firstName $lastName
Email: $email
Phone: $phone

-------------------------------
LOAN DETAILS
-------------------------------
Loan Type: $loanType
Amount Requested: $amount
Property Location: $location

-------------------------------
ADDRESS
-------------------------------
Street: $street
City: $city
State: $state
Zip Code: $zipcode

-------------------------------
FINANCIAL INFO
-------------------------------
Estimated Credit Score: $creditScore
Income Range: $incomeRange
Application Plan: $applicationDate

-------------------------------
ADDITIONAL COMMENTS
-------------------------------
$comments
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
