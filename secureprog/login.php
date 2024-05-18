<?php
session_start();

// Custom error handling function
function customError($errno, $errstr) {
    $errors = "Error: [$errno] $errstr";
    header("Location: login.html?errors=$errors");
    exit;
}

// Set custom error handler
set_error_handler("customError");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        // Get email and password from the form ad Sanitized using MYSQLI
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password =mysqli_real_escape_string($conn, $_POST['password']); 

        // Prepare the SQL statement with placeholders
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");

        // Bind parameters to the placeholders
        $stmt->bind_param("s", $email);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if the query executed successfully
        if (!$result) {
            throw new Exception("Error executing query: " . $conn->error);
        }

        // Check if user was found
        if ($result->num_rows > 0) {
            // User found, verify password
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Password is correct, set session variables and redirect
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                header("Location: homepage.html"); // Redirect to homepage
                exit;
            } else {
                // Password is incorrect
                $errors = "Invalid email or password.";
                header("Location: login.html?errors=$errors");
            }
        } else {
            // User not found
            $errors = "User not found.";
            header("Location: login.html?errors=$errors");
        }

        // Close the statement
        $stmt->close();

        // Close the connection
        $conn->close();
    } catch (Exception $e) {
        $errors = $e->getMessage();
        header("Location: login.html?errors=$errors");
        exit;
    }
}
?>
