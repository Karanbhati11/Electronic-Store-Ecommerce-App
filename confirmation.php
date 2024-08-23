<?php
session_start();
$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation - Electronix Store</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="./images/electronics.png" type="image/x-icon">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #ffffff;
            color: #343a40;
        }

        .confirmation-container {
            max-width: 600px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 3px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .confirmation-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #86a194;
        }

        .confirmation-container p {
            font-size: 1.25rem;
            margin-bottom: 30px;
            color: #555;
        }

        .confirmation-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .confirmation-buttons .btn {
            padding: 12px 30px;
            font-size: 1rem;
            border-radius: 3px;
            transition: background-color 0.3s ease-in-out;
        }

        .confirmation-buttons .btn-primary {
            background-color: #86a194;
            border-color: #86a194;
            color: #ffffff;
        }

        .confirmation-buttons .btn-primary:hover {
            background-color: #6f867c;
            border-color: #6f867c;
        }

        .confirmation-buttons .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #ffffff;
        }

        .confirmation-buttons .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .confirmation-image {
            margin-top: 20px;
            max-width: 100px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="confirmation-container">
        <img src="images/thanks.png" alt="Order Confirmation" class="confirmation-image">
        <h1>Thank You!</h1>
        <p>Your order has been placed successfully!</p>
        <div class="confirmation-buttons">
            <a href="invoice.php?order_id=<?php echo $order_id; ?>" class="btn btn-primary">Download Invoice</a>
            <a href="index.php" class="btn btn-dark">Continue Shopping</a>
        </div>
    </div>
</body>

</html>