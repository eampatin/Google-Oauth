<?php
require_once 'config.php'; // Google Client Configuration
require_once 'vendor/autoload.php';

if (isset($_SESSION['user_token'])) {
    // If the user is already logged in, redirect to home
    header("Location: home.php");
    exit();
}

// Set the 'select_account' prompt to force Google to show the account selection screen
$client->setPrompt('select_account');

// Generate the Google sign-in URL
$authUrl = $client->createAuthUrl();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body><br><br><br><br><br><br><br><br><br><br>
    <div class="container" id="signup" style="display:none;">
        <h1 class="form-title">Register</h1>
        <form id="registerForm">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" class="btn" value="Sign Up">
            <p id="signUpError" style="color: red;"></p>
        </form>
        <p class="or">----------or--------</p>
        <div class="icons">
            <i class="fab fa-google"></i>
            <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
            <p>Already Have Account?</p>
            <button id="signInButton">Sign In</button>
        </div>
    </div>

    <div class="container" id="signIn">  <!--just change type email to text to make it type any symbols -->
        <h1 class="form-title">Sign In</h1>
        <form id="signInForm">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="text" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <p class="recover"><a href="#">Recover Password</a></p>
            <input type="submit" class="btn" value="Sign In">
            <p id="signInError" style="color: red;"></p>
        </form>
        <p class="or">----------or--------</p>
        <div class="icons">
            <a href="<?php echo htmlspecialchars($authUrl); ?>"><i class="fab fa-google"></i></a>
            <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
            <p>Don't have an account yet?</p>
            <button id="signUpButton">Sign Up</button>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
