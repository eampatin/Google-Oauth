<?php
require_once 'config.php'; // Ensure this includes DB connection
require_once 'vendor/autoload.php';

// session_start(); // Start the session

if (isset($_GET['code'])) {
    try {
        // Exchange the authorization code for an access token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        // Check for errors in the token response
        if (isset($token['error'])) {
            throw new Exception('Error fetching access token: ' . $token['error']);
        }

        $client->setAccessToken($token['access_token']);
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        // Debug: Check Google user info
        echo '<pre>';
        print_r($google_account_info);
        echo '</pre>';

        $userinfo = [
            'email' => $google_account_info->email,
            'first_name' => $google_account_info->givenName,
            'last_name' => $google_account_info->familyName,
            'full_name' => $google_account_info->name,
            'picture' => $google_account_info->picture,
            'verifiedEmail' => $google_account_info->verifiedEmail,
            'token' => $google_account_info->id,
        ];

        // Check if user exists in DB
        $email = mysqli_real_escape_string($conn, $userinfo['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Existing user
            $userinfo = mysqli_fetch_assoc($result);
            $token = $userinfo['token'];
        } else {
            // New user - insert into DB
            $sql = "INSERT INTO users (email, first_name, last_name, full_name, picture, verifiedEmail, token)
                    VALUES (
                        '{$userinfo['email']}',
                        '{$userinfo['first_name']}',
                        '{$userinfo['last_name']}',
                        '{$userinfo['full_name']}',
                        '{$userinfo['picture']}',
                        '{$userinfo['verifiedEmail']}',
                        '{$userinfo['token']}'
                    )";

            if (mysqli_query($conn, $sql)) {
                $token = $userinfo['token'];
            } else {
                throw new Exception('Database insert failed: ' . mysqli_error($conn));
            }
        }

        $_SESSION['user_token'] = $token;

        // Redirect to home page
        header("Location: home.php");
        exit;

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        die();
    }
} else {
    // Check if user is already logged in
    if (isset($_SESSION['user_token'])) {
        $token = mysqli_real_escape_string($conn, $_SESSION['user_token']);
        $sql = "SELECT * FROM users WHERE token = '$token'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $userinfo = mysqli_fetch_assoc($result);
        } else {
            echo 'Invalid session. Please log in again.';
            session_destroy();
            header("Location: google-auth.php");
            exit;
        }
    } else {
        echo 'No active session. Redirecting to login...';
        header("Location: google-auth.php");
        exit;
    }
}
?>

<body>
    <h1>Welcome, <?php echo htmlspecialchars($userinfo['full_name']); ?>!</h1>
    <img src="<?php echo htmlspecialchars($userinfo['picture']); ?>" alt="Profile Picture">
    <p>Email: <?php echo htmlspecialchars($userinfo['email']); ?></p>
    <p>First Name: <?php echo htmlspecialchars($userinfo['first_name']); ?></p>
    <p>Last Name: <?php echo htmlspecialchars($userinfo['last_name']); ?></p>
    <p>Verified Email: <?php echo htmlspecialchars($userinfo['verifiedEmail']); ?></p>
    <!-- <p>Token: <?php echo htmlspecialchars($userinfo['token']); ?></p> -->
    <a href="logout.php">Logout</a>
</body>
