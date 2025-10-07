<?php
header('Content-Type: application/json');

// Disable error display in production
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Your company email
$to = "contact@bluepeaklending.net"; // CHANGE THIS TO YOUR COMPANY EMAIL

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get and sanitize form data
    $firstName = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $lastName = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email_input'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $dob = htmlspecialchars(trim($_POST['dob'] ?? ''));
    $street = htmlspecialchars(trim($_POST['u_street'] ?? ''));
    $city = htmlspecialchars(trim($_POST['u_town'] ?? ''));
    $state = htmlspecialchars(trim($_POST['u_state'] ?? ''));
    $zipcode = htmlspecialchars(trim($_POST['u_zipcode'] ?? ''));
    $comments = htmlspecialchars(trim($_POST['comments'] ?? ''));
    
    $fullName = $firstName . ' ' . $lastName;
    
    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all required fields.'
        ]);
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email address.'
        ]);
        exit;
    }
    
    // Email subject
    $subject = "New Application Submission - $fullName";
    
    // Email body
    $message = "
NEW APPLICATION SUBMISSION
==========================

Applicant Information:
----------------------
Full Name: $fullName
Email: $email
Phone: $phone
Date of Birth: $dob

Address:
--------
State: $state
City: $city
Street: $street
Zip Code: $zipcode

Additional Comments:
--------------------
$comments

Submitted on: " . date('Y-m-d H:i:s') . "
";
    
    // Email headers
    $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Handle file upload if present
    $fileAttached = false;
    if (isset($_FILES['u_resume']) && $_FILES['u_resume']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['u_resume']['tmp_name'];
        $fileName = $_FILES['u_resume']['name'];
        $fileSize = $_FILES['u_resume']['size'];
        $fileType = $_FILES['u_resume']['type'];
        
        // Check file size (10MB max)
        if ($fileSize > 10485760) {
            echo json_encode([
                'success' => false,
                'message' => 'File size exceeds 10MB limit.'
            ]);
            exit;
        }
        
        // Read file content
        $fileContent = file_get_contents($fileTmpPath);
        $fileContent = chunk_split(base64_encode($fileContent));
        
        // Create boundary
        $boundary = md5(time());
        
        // Update headers for attachment
        $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
        
        // Create message with attachment
        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $message . "\r\n";
        
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: {$fileType}; name=\"{$fileName}\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n";
        $body .= $fileContent . "\r\n";
        $body .= "--{$boundary}--";
        
        $message = $body;
        $fileAttached = true;
    }
    
    // Send email
    if (mail($to, $subject, $message, $headers)) {
        $successMsg = 'Application submitted successfully!';
        if ($fileAttached) {
            $successMsg .= ' Resume attached.';
        }
        echo json_encode([
            'success' => true,
            'message' => $successMsg
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to send email. Please try again later.'
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>