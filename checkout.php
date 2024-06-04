<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "balsadb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data sent from the client
$data = json_decode(file_get_contents("php://input"), true);

// Insert each item in the cart into the purchases table
foreach ($data as $productId => $productDetails) {
    $title = $conn->real_escape_string($productDetails['title']);
    $description = $conn->real_escape_string($productDetails['description']);
    $price = $productDetails['price'];
    $quantity = $productDetails['quantity'];
    $img = $conn->real_escape_string($productDetails['img']);
    $date_added = date('Y-m-d H:i:s'); // Current timestamp

    $sql = "INSERT INTO purchases (title, description, price, quantity, img, date_added)
            VALUES ('$title', '$description', '$price', '$quantity', '$img', '$date_added')";

    if ($conn->query($sql) === TRUE) {
        $response['status'] = "success";
    } else {
        $response['status'] = "error";
        $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
        break; // Exit loop on first error
    }
}

$conn->close();

// Send response back to the client
header('Content-Type: application/json');
echo json_encode($response);
?>