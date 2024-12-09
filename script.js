const signUpButton = document.getElementById("signUpButton");
const signInButton = document.getElementById("signInButton");
const signInForm = document.getElementById("signIn");
const signUpForm = document.getElementById("signup");

// Toggle between Sign Up and Sign In forms
signUpButton.addEventListener("click", function () {
  signInForm.style.display = "none";
  signUpForm.style.display = "block";
});

signInButton.addEventListener("click", function () {
  signInForm.style.display = "block";
  signUpForm.style.display = "none";
});

// Handle Register Form Submission
$("#registerForm").on("submit", function (e) {
  e.preventDefault(); // Prevent default form submission
  $.ajax({
    type: "POST",
    url: "register.php",
    data: $(this).serialize() + "&action=signUp", // Include action parameter
    dataType: "json",
    success: function (response) {
      if (response.success) {
        window.location.href =
          "index.php?message=" + encodeURIComponent(response.message);
      } else {
        $("#signUpError").text(response.message); // Display error message
      }
    },
  });
});

// Handle Sign In Form Submission
$("#signInForm").on("submit", function (e) {
  e.preventDefault(); // Prevent default form submission
  $.ajax({
    type: "POST",
    url: "register.php",
    data: $(this).serialize() + "&action=signIn", // Include action parameter
    dataType: "json",
    success: function (response) {
      if (response.success) {
        window.location.href = "homepage.php"; // Redirect to homepage on success
      } else {
        $("#signInError").text(response.message); // Display error message
      }
    },
  });
});
