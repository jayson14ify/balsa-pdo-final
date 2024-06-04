<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome CSS -->
    <style>
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 80%;
            margin: 0 auto;
        }

        .card {
            display: flex;
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add smooth transitions */
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            padding: 10px;
        }
        .card:hover {
            transform: translateY(-10px); /* Move the card up when hovered */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Add shadow effect */
        }

        .card-img-top {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            width: 100%;
        }

        .add-to-cart-button {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .add-to-cart-button button {
            width: 100%;
        }

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
            background-image: url('https://i.pngimg.me/thumb/f/720/5996145641783296.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-color: #f8f9fa;
        }
        .cart-button-space {
            margin-top: 96px; /* Increased space between Remove and Checkout All buttons to 96px (1 inch) */
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
    <div id="cartContainer"></div>

    <!-- Modal for adding to cart -->
    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addToCartModalLabel">Add to Cart</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="addToCartButton" class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('./products/products-api.php')
            .then(response => response.json())
            .then(data => {
                const booksContainer = document.getElementById('productsDisplay');
                data.forEach(product => {
                    const cardHTML = `
                    <div class="card">
                        <img class="card-img-top" src="${product.img}">
                        <div class="card-body">
                            <h5 class="card-title">${product.title}</h5><br>Price: ₱${product.rrp}<br>
                            <p class="card-text">${product.description}.</p>
                            <p class="card-text">Quantity: ${product.quantity}</p>
                            <div class="add-to-cart-button">
                                <button class="btn btn-success" onclick="openAddToCartModal(${product.id}, 0)">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    `;
                    booksContainer.innerHTML += cardHTML;
                });
            })
            .catch(error => console.error('Error:', error));

        let cart = {};

        function openAddToCartModal(productId, quantityInCart) {
            const modalBody = document.querySelector('#addToCartModal .modal-body');
            modalBody.innerHTML = `
                <p>Product ID: ${productId}</p>
                <label for="quantityInput">Quantity:</label>
                <input type="number" id="quantityInput" min="1" value="${quantityInCart}" class="form-control">
            `;
            $('#addToCartModal').modal('show');
            document.querySelector('#addToCartButton').addEventListener('click', function() {
                const quantity = document.querySelector('#quantityInput').value;
                addToCart(productId, quantity);
                $('#addToCartModal').modal('hide');
            });
        }

        function addToCart(productId, quantity) {
        quantity = parseInt(quantity);
        if (cart[productId]) {
            cart[productId] += quantity;
        } else {
            cart[productId] = quantity;
        }
        displayCart();

        }

        function removeFromCart(productId) {
            delete cart[productId];
            displayCart();
        }

        function checkoutAll() {
            fetch('purchases.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(cart)
            })
            .then(response => response.text())
            .then(data => {
                console.log('Success:', data);
                cart = {};
                displayCart();
                window.location.href = "purchases.php"; 
            })
            .catch(error => console.error('Error:', error));
        }

        function displayCart() {
        const cartContainer = document.getElementById('cartContainer');
        let cartHTML = '<h3>Cart</h3>';
        for (const [productId, quantity] of Object.entries(cart)) {
            cartHTML += `
                    <div>
                        <p>Product ID: ${productId}, Quantity: ${quantity}</p>
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${productId})">Remove</button>
                        <br><br> <!-- Adding line breaks for extra space -->
                    </div>
                `;
            }
            if (Object.keys(cart).length > 0) {
                cartHTML += `<button class="btn btn-primary" onclick="checkoutAll()"><i class="fas fa-cart-plus"></i>  Checkout All</button>`;
            }
            cartContainer.innerHTML = cartHTML;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
