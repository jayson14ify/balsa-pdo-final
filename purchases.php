<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchased Items</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome CSS -->
    <style>
        /* Define a class for the grid */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Responsive grid with minimum item width of 250px */
            gap: 20px; /* Gap between grid items */
            padding: 20px; /* Add padding around the grid container */
            width: 80%; /* Set width to 80% */
        }

        /* Style for individual cards */
        .card {
            width: 100%; /* Ensure cards take full width of their container */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add smooth transitions */
            background-color: #ecffed;
        }

        .card:hover {
            transform: translateY(-10px); /* Move the card up when hovered */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Add shadow effect */
        }

        .card-img-top {
            width: 100%; /* Ensure the image fills its container */
            height: auto; /* Maintain aspect ratio */
            object-fit: cover; /* Ensure the image covers the entire container */
        }

        /* Full width for purchased items section */
        #purchased {
            width: 100%; /* Full width */
            position: relative; /* Not fixed */
            top: 0; /* Reset top */
            right: 0; /* Reset right */
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 999;
            margin-top: 20px; /* Adjust as needed */
            overflow-y: auto; /* Enable vertical scrollbar */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="https://res.cloudinary.com/inhouse-orders/image/upload/v1604651771/pho_cafe_favicon_uvdp5d.png" width="30" height="30" class="d-inline-block align-top" alt="">
            DIWATA PARES
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Products<span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <!-- Purchased Items Section -->
    <div id="purchased" class="card-grid">
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "balsadb";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch purchased items from the purchases table
        $sql = "SELECT * FROM purchases ORDER BY purchase_date DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                // Display each purchased item as a card
                echo "<div class='card m-2' style='width: 18rem;'>
                        <img class='card-img-top' src='" . $row["img"] . "'>
                        <div class='card-body'>
                            <h5 class='card-title'>" . $row["title"] . "</h5>
                            <p class='card-text'>Description: " . $row["description"] . "</p>
                            <p class='card-text'>Price: ₱" . $row["price"] . "</p>
                            <p class='card-text'>Quantity: " . $row["quantity"] . "</p>
                        </div>
                      </div>";
            }
        } else {
            echo "<p>No purchased items yet.</p>";
        }
        $conn->close();
        ?>
    </div>

    <!-- Add Bootstrap and Font Awesome links -->
    <script src
    ="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>