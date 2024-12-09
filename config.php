<?php

require_once 'vendor/autoload.php';  // Include Composer autoloader
use Dotenv\Dotenv;

session_start();

// Check if the .env file exists in the current directory
// if (file_exists(__DIR__ . '/.env')) {
//     echo ".env file is present.<br>";
// } else {
//     echo ".env file is missing.<br>";
// }

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Debugging: Check if environment variables are loaded correctly
$clientId = $_ENV['clientId'];  // Correct way to access .env variables using $_ENV
$clientSecret = $_ENV['clientSecret'];  // Correct way to access .env variables using $_ENV
$redirectUri = "http://localhost/oauth/home.php"; // Make sure this matches the OAuth redirect URI in your Google console

// Initialize Google Client
$client = new Google_Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('email');
$client->addScope('profile');

// Database connection details
$hostname = "localhost";
$username = "root";
$password = "";
$database = "oauth";

$conn = mysqli_connect($hostname, $username, $password, $database);

?>
