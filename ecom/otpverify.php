<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user-entered OTP
    $userOTP = $_POST['otp'];

    // Check if the OTP matches the one stored in the session
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $userOTP) {
        // OTP verification successful
        echo 'OTP verification successful. Proceed with further actions.';
        // Reset the OTP session variable
        unset($_SESSION['otp']);
        // TODO: Add your further actions here
        header('Location: profile.php');

    } else {
        // OTP verification failed
        echo 'Invalid OTP. Please try again.';
    }
}
?>

<!-- HTML form for OTP verification -->
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="otp">Enter OTP:</label>
    <input type="text" name="otp" id="otp" required>
    <button type="submit">Verify OTP</button>
</form>
