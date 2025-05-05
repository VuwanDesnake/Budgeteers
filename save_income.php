<?php
// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method not allowed
    echo "405 Method Not Allowed";
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Walletok";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$amount = $_POST['amount'] ?? '';
$source = $_POST['source_hidden'] ?? '';
$date = $_POST['income_date'] ?? '';

// Debugging - Check received data
file_put_contents("debug_log.txt", "Received POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

// Validate the data
if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
    echo "Invalid amount.";
    exit;
}

if (empty($date)) {
    echo "Date is required.";
    exit;
}

// Insert into database
$sql = "INSERT INTO income (amount, source, income_date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("dss", $amount, $source, $date);

if ($stmt->execute()) {
    echo "Income saved successfully!";
} else {
    // Log error message to debug the issue
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
