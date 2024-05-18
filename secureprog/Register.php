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

    // Sample data for inserting into the user table
    $email = $_POST["email"];
    $password = $_POST["password"];

    //Sanitized using MYSQLI
    $sanitized_email = mysqli_real_escape_string($conn, $_POST["email"]); // Escape email input
    $sanitized_password = mysqli_real_escape_string($conn, $_POST["password"]); // Escape email input

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert data into the user table
    $sql = "INSERT INTO user (email, password) VALUES (?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        echo "New record created successfully";
        header("Location: login.html");
        exit();
    } else {
        throw new Exception("Error: " . $sql . "<br>" . $conn->error);
    }

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
