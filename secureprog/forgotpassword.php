<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sp"; // Database name

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $email = mysqli_real_escape_string($conn, $_POST["email"]); // Sanitize the email input

    // Sample data for retrieving user by email
    $email = $_POST["email"];

    // Prepare the SQL statement with placeholder
    $sql = "SELECT * FROM user WHERE email = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("s", $email);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // User found, send password reset email
        // Here you can implement the logic to send the reset password link via email
        $message = "Password reset link sent to $email";
        echo "<div style='background-color: #dff0d8; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 20px;'>$message</div>";
    } else {
        // User not found
        $error = "User not found.";
        header("Location: login.html?errors=$error");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Close the statement
    if (isset($stmt)) {
        $stmt->close();
    }
    // Close the connection
    if (isset($conn)) {
        $conn->close();
    }
}
?>
