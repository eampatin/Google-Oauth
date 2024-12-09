<?php
session_start();

// Revoke Google Token (if stored)
if (isset($_SESSION['user_token'])) {
    $token = $_SESSION['user_token'];
    $revokeUrl = "https://accounts.google.com/o/oauth2/revoke?token=" . $token;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $revokeUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Debug: Log the revocation response
    // echo $response;
}

// Unset user session and destroy
unset($_SESSION['user_token']);
session_destroy();

// Redirect to the login page
header("Location: index.php");
exit();
?>
