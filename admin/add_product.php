<?php
require_once '../includes/db.php';
session_start();

// Check if user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch categories for the dropdown
$category_query = "SELECT id, name FROM Categories";
$category_result = mysqli_query($link, $category_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $image_url = mysqli_real_escape_string($link, $_POST['image_url']);

    // Prepare statement to prevent SQL injection
    $stmt = $link->prepare("INSERT INTO Products (name, description, price, category_id, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $description, $price, $category_id, $image_url);

    if ($stmt->execute()) {
        header("Location: admin_products.php?success=1");
        exit();
    } else {
        echo "ERROR: Could not execute query. " . mysqli_error($link);
    }

    $stmt->close();
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Product - Electronix Store</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../images/electronics.png" type="image/x-icon">

    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding-top: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #86a194;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 0;
            border-color: #86a194;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #6f867c;
        }

        .btn-primary {
            background-color: #86a194;
            border-color: #86a194;
            font-weight: 600;
            border-radius: 0;
            color: #ffffff;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #6f867c;
            border-color: #6f867c;
        }
    </style>
</head>

<body>
    <?php include '../navbar.php'; ?>

    <div class="container mt-5">
        <h1>Add New Product</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php while ($category = mysqli_fetch_assoc($category_result)): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image_url">Image URL:</label>
                <input type="text" class="form-control" id="image_url" name="image_url" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</body>

</html>