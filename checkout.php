<?php
require_once 'classes/Order.php';
require_once 'classes/Product.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Initialize cart and total variables
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

// Fetch product details for each item in the cart
foreach ($cart as &$item) {
    $product = Product::getProductById($item['product_id']);
    if ($product) {
        $item['name'] = $product['name'];
        $item['image_url'] = $product['image_url'];
        $item['price'] = $product['price']; // Ensure price is updated correctly
    }
    $total += $item['quantity'] * $item['price'];
}
unset($item); // Break the reference with the last element

// Handle form submission for placing order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error_message = '';

    if (empty($cart)) {
        $error_message = "Your cart is empty. Please add items to the cart before checking out.";
    } else {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
        $zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_STRING);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

        if (!preg_match('/^\d{10}$/', $phone)) {
            $error_message = "Please enter a valid 10-digit phone number.";
        }

        if (!$name || !$address || !$city || !$state || !$zip || !$phone) {
            $error_message = "All fields are required.";
        }

        if (empty($error_message)) {
            $user_id = $_SESSION['id'];
            $shipping_details = [
                'name' => $name,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'phone' => $phone
            ];

            $order_id = Order::createOrder($user_id, $total, $shipping_details);
            if ($order_id) {
                foreach ($cart as $item) {
                    Order::addOrderItem($order_id, $item['product_id'], $item['quantity'], $item['price']);
                }
                $_SESSION['cart'] = []; // Clear the cart
                header("Location: confirmation.php?order_id=$order_id");
                exit();
            } else {
                $error_message = "Failed to create order.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="./images/electronics.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 40px auto;
            display: flex;
            gap: 20px;
        }

        .order-summary {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 3px;
            width: 700px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .checkout-form {
            flex: 2;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 3px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .checkout-form h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
            color: #86a194;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            padding: 10px;
            font-size: 1rem;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        .form-group input:focus {
            border-color: #86a194;
        }

        .btn-primary {
            background-color: #86a194;
            border-color: #86a194;
            padding: 12px 20px;
            font-size: 1rem;
            border-radius: 3px;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #6f867c;
            border-color: #6f867c;
        }

        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 3px;
            display: block;
            margin: 0 auto;
        }

        .cart-item .card-body {
            padding-left: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-item .card-body div {
            margin-right: 10px;
            text-align: center;
        }

        .total-container {
            text-align: right;
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 20px;
            color: #333;
        }

        .accordion .card-header {
            background-color: #86a194;
            color: white;
            cursor: pointer;
            border-radius: 3px;
        }

        .accordion .card-header button {
            font-size: 1rem;
            color: white;
            text-align: center;
            width: 100%;
        }

        .accordion .card-header:hover {
            background-color: #6f867c;
        }

        .accordion .card-body {
            padding: 20px;
            background-color: #f9f9f9;
        }

        .accordion .collapse.show {
            border-top: 2px solid #86a194;
        }
    </style>

    <script>
        function validateForm() {
            const phone = document.getElementById('phone').value;
            const phonePattern = /^\d{10}$/;
            if (!phonePattern.test(phone)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="checkout-container">
        <div class="order-summary">
            <div class="accordion" id="orderSummaryAccordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn" type="button" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                View Order Summary
                            </button>
                        </h2>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                        data-parent="#orderSummaryAccordion">
                        <div class="card-body">
                            <div id="cart-items">
                                <?php foreach ($cart as $item): ?>
                                    <div class="card mb-3 cart-item" data-product-id="<?php echo $item['product_id']; ?>">
                                        <div class="row no-gutters">
                                            <div class="col-md-5 d-flex align-items-center">
                                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                                    class="card-img" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                            </div>
                                            <div class="col-md-7">
                                                <div class="card-body d-flex flex-column justify-content-center">
                                                    <div><?php echo htmlspecialchars($item['name']); ?></div>
                                                    <div>$<?php echo htmlspecialchars($item['price']); ?></div>
                                                    <div>Qty: <?php echo htmlspecialchars($item['quantity']); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="total-container">
                                Total: $<span id="total-amount"><?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="checkout-form">
            <h1>Checkout</h1>
            <?php if (isset($error_message) && $error_message): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form action="checkout.php" method="post" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" class="form-control" name="address" id="address" required>
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" class="form-control" name="city" id="city" required>
                </div>
                <div class="form-group">
                    <label for="state">State:</label>
                    <input type="text" class="form-control" name="state" id="state" required>
                </div>
                <div class="form-group">
                    <label for="zip">Zip Code:</label>
                    <input type="text" class="form-control" name="zip" id="zip" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" class="form-control" name="phone" id="phone" required>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Place Order</button>
            </form>
        </div>
    </div>

    <?php include("footer.php"); ?>
</body>

</html>