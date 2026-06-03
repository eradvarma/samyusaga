<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = strip_tags(trim($_POST["first_name"] ?? ""));
    $last_name  = strip_tags(trim($_POST["last_name"] ?? ""));
    $email      = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $service    = strip_tags(trim($_POST["service"] ?? ""));
    $message    = strip_tags(trim($_POST["message"] ?? ""));

    $errors = [];
    if (empty($first_name)) $errors[] = "First name is required.";
    if (empty($last_name))  $errors[] = "Last name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($message))    $errors[] = "Message is required.";

    if (!empty($errors)) {
        $error_str = implode("\\n", $errors);
        header("Location: index.html?error=" . urlencode($error_str));
        exit;
    }

    $to      = "rahulshivkantmishra@gmail.com";
    $subject = "New Enquiry from SamyuSaga — $first_name $last_name";

    $body  = "You have received a new enquiry from the SamyuSaga website.\n\n";
    $body .= "Name: $first_name $last_name\n";
    $body .= "Email: $email\n";
    $body .= "Service Interest: $service\n";
    $body .= "Message:\n$message\n";

    $headers  = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $body, $headers)) {
        header("Location: index.html?success=1");
    } else {
        header("Location: index.html?error=" . urlencode("Something went wrong. Please try again."));
    }
    exit;
}

header("Location: index.html");
exit;
