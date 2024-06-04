<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome CSS -->
    <style>
        /* Define a class for the grid */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Responsive grid with minimum item width of 250px */
            gap: 20px; /* Gap between grid items */
            padding: 20px; /* Add padding around the grid container */
            max-width: 80%; /* Set maximum width to 80% */
            margin: 0 auto; /* Center the grid horizontally */
        }

        /* Style for individual cards */
        .card {
            width: 18rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 1px solid #ccc; /* Border added */
            border-radius: 5px; /* Rounded corners */
            background-color: #fff; /* White background */
            padding: 10px; /* Padding added */
        }

        .card-img-top{
            width: 100%; /* Ensure the image fills its container */
            height: 300px; /* Fixed height for all images */
            object-fit: cover; /* Ensure the image covers the entire container */
        }

        /* Style for the cart */
        #cartContainer {
            position: fixed;
            top: 4em;
            right: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }
        body {
            background-image: url('https://i.pngimg.me/thumb/f/720/5996145641783296.jpg'); /* Replace 'your-image-url.jpg' with the URL of your background image */
            background-size: cover; /* Cover the entire viewport */
            background-repeat: no-repeat; /* Do not repeat the background image */
            background-position: center center; /* Center the background image */
            /* Optionally, you can add a fallback background color */
            background-color: #f8f9fa; /* Fallback background color */
        }

        /* Center content */
        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Adjust height as needed */
        }
    </style>
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <img src="https://res.cloudinary.com/inhouse-orders/image/upload/v1604651771/pho_cafe_favicon_uvdp5d.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Diwata Pares
    </a>
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Products<span class="sr-only">(current)</span></a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
</nav>
<body>

    <div id="productsDisplay" class="card-grid"></div>
    <!-- Cart Display Area -->
    
    <div id="cartContainer"></div>

    <script>
        fetch('./products/products-api.php')
            .then(response => response.json())
            .then(data => {
                const booksContainer = document.getElementById('productsDisplay');
                data.forEach(product => {
                    const cardHTML = `
                    

                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="${product.img}">
                            <div class="card-body">
                                <h5 class="card-title">${product.title}</h5><br>Price: ₱${product.rrp}<br>
                                <p class="card-text">${product.description}.</p>
                                <p class="card-text"<br>Quantity: ${product.quantity}</p>
                                 <button class="btn btn-success" onclick="addToCart(${product.id})">
                                    <i class="fas fa-cart-plus"></i> <!-- Add to Cart icon -->
                                Add to Cart
                            </button>
                            </div>
                    </div>

                    `;
                    booksContainer.innerHTML += cardHTML;
                });
            })
            .catch(error => console.error('Error:', error));

        // Initialize cart object
        let cart = {};

        // Function to add a product to the cart
        function addToCart(productId) {
            // Add the product to the cart
            if (cart[productId]) {
                cart[productId]++;
            } else {
                cart[productId] = 1;
            }
            // Display the updated cart
            displayCart();
            
        }

        // Function to remove a product from the cart
        function removeFromCart(productId) {
            // Check if the product is in the cart
            if (cart[productId]) {
                cart[productId]--;
                if (cart[productId] === 0) {
                    delete cart[productId];
                }
                // Display the updated cart
                displayCart();
            }
        }
            // Function to handle checkout for all items in the cart
            function checkoutAll() {
            // Redirecting your purchase page URL to "products-api.php"
            window.location.href = "purchases.php"; 
        }

        // Function to display the cart with the items added and deduct the values from the quantity data field
                function displayCart() {
                    const cartContainer = document.getElementById('cartContainer');
                    let cartHTML = '<h3>Cart</h3>';
                    
                    // Check if the cart is empty
                    if (Object.keys(cart).length === 0) {
                        cartHTML += '<p>Your cart is empty.</p>';
                    } else {
                        // Iterate over the cart items and display them
                        for (const [productId, quantity] of Object.entries(cart)) {
                            cartHTML += `
                                <div>
                                    <p>Product ID: ${productId}, Quantity: ${quantity}</p>
                                    <button class="btn btn-danger btn-sm" onclick="removeFromCart(${productId})">Remove</button>
                                </div>
                            `;
                            // Here, you can update the quantity data field for the corresponding product
                        }
                        
                        // Add space above the Checkout button
                        cartHTML += '<br><br>'; // You can add more line breaks or empty divs here for additional space
                        
                        // Add Checkout All button
                        cartHTML += `
                            <button class="btn btn-primary" onclick="checkoutAll()">Checkout All</button>
                        `;
                    }
                    
                    // Update the cart display
                    cartContainer.innerHTML = cartHTML;
            }

    </script>
</body>
</html>
