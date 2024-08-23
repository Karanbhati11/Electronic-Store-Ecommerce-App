<?php
require_once '../includes/db.php';  // Update path to database connection
session_start();

// Check if user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");  // Redirect to the main index if not admin
    exit();
}

// Fetch products
$query = "SELECT p.id, p.name, p.description, p.price, c.name AS category FROM Products p 
          JOIN Categories c ON p.category_id = c.id";
$result = mysqli_query($link, $query);

// Handle product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $product_id = intval($_POST['product_id']);
    $delete_query = "DELETE FROM Products WHERE id = $product_id";
    mysqli_query($link, $delete_query);
    header("Location: admin_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Products - Electronix Store</title>
    <link rel="icon" href="../images/electronics.png" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding-top: 20px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #86a194;
            margin-bottom: 30px;
            text-align: center;
        }

        .btn-primary {
            background-color: #86a194;
            border-color: #86a194;
            font-weight: 600;
            border-radius: 0;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #6f867c;
            border-color: #6f867c;
        }

        .table {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #86a194;
            color: #ffffff;
            font-weight: 600;
        }

        .table td {
            color: #555;
        }

        .btn-warning,
        .btn-danger {
            font-weight: 600;
            border-radius: 0;
            margin-right: 5px;
        }

        .btn-warning {
            background-color: #ffffff;
            border-color: #86a194;
            color: #86a194;
        }

        .btn-warning:hover {
            background-color: #86a194;
            border-color: #6f867c;
            color: #ffffff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .table-action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .table-action-buttons form {
            margin: 0;
        }
    </style>
</head>

<body>
    <?php include '../navbar.php'; ?>

    <div class="container">
        <h1>Manage Products</h1>
        <div class="text-right mb-3">
            <a href="add_product.php" class="btn btn-primary">Add New Product</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td class="table-action-buttons">
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="delete_product" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>