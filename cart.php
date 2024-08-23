<?php
require_once 'classes/Product.php';
session_start();

// Use session for storing the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$total = 0;

// Fetch product details for each item in the cart
foreach ($cart as &$item) {
    $product = Product::getProductById($item['product_id']);
    if ($product) {
        $item['name'] = $product['name'];
        $item['image_url'] = $product['image_url'];
        $item['description'] = $product['description'];
        $item['price'] = $product['price']; // Ensure price is updated correctly
    }
    $total += $item['quantity'] * $item['price'];
}
unset($item); // Break the reference with the last element

// Handle remove item from cart
if (isset($_POST['remove'])) {
    $removeId = (int) $_POST['remove'];
    foreach ($cart as $key => $item) {
        if ((int) $item['product_id'] === $removeId) {
            unset($cart[$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($cart);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart - Electronix Store</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="icon" href="./images/electronics.png" type="image/x-icon">

</head>

<body>
    <?php include("navbar.php"); ?>

    <div class="container cart-container mt-5">
        <h1 class="text-center mb-5">Your Cart</h1>
        <div id="cart-items" class="cart">
            <?php if (!empty($cart)): ?>
                <?php foreach ($cart as $item): ?>
                    <div class="cart-item row mb-4">
                        <div class="col-md-3">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($item['name']); ?>" class="img-fluid rounded">
                        </div>
                        <div class="col-md-7 cart-item-details">
                            <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="text-muted"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="font-weight-bold">Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                            <p class="font-weight-bold">Price: $<?php echo htmlspecialchars($item['price']); ?></p>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <form method="post" action="cart.php" class="w-100">
                                <input type="hidden" name="remove" value="<?php echo $item['product_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-block">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Your cart is empty. <a href="products.php">Start shopping</a></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($cart)): ?>
            <div class="total-container text-center mt-4">
                <h2>Total: $<span id="total-amount"><?php echo number_format($total, 2); ?></span></h2>
                <a href="checkout.php" class="btn btn-primary btn-lg mt-3">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
    <?php include("footer.php"); ?>
</body>

</html>