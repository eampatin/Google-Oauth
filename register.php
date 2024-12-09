<?php 
include 'connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // Determine the action type: signUp or signIn
    $response = ['success' => false, 'message' => '']; // Default response

    if ($action === 'signUp') {
        $firstName = $_POST['fName'];
        $lastName = $_POST['lName'];
        $email = $_POST['email'];
        $password = md5($_POST['password']); // Hash the password

        // Check if email already exists
        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            $response['message'] = "Email Address Already Exists!";
        } else {
            // Insert new user
            $insertQuery = "INSERT INTO users(firstName, lastName, email, password) VALUES ('$firstName', '$lastName', '$email', '$password')";
            if ($conn->query($insertQuery) === TRUE) {
                $response['success'] = true;
                $response['message'] = 'Registration Successful';
            } else {
                $response['message'] = "Error: " . $conn->error;
            }
        }
    }

    if ($action === 'signIn') {
        $email = $_POST['email'];
        $password = md5($_POST['password']); // Hash the password

        // Verify user credentials
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $_SESSION['email'] = $email;
            $response['success'] = true;
            $response['message'] = 'Login Successful';
        } else {
            $response['message'] = "Not Found, Incorrect Email or Password";         
        }
    }

    // Return JSON response
    echo json_encode($response);
}
?>


