<?php
require_once '../includes/db.php';
session_start();

// Check if user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$category_id = $_GET['id'] ?? null;
if (!$category_id) {
    header("Location: admin_categories.php");
    exit();
}

// Fetch category details
$category_query = "SELECT * FROM Categories WHERE id = $category_id";
$category_result = mysqli_query($link, $category_query);
$category = mysqli_fetch_assoc($category_result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($link, $_POST['name']);

    $update_query = "UPDATE Categories SET name = '$name' WHERE id = $category_id";

    if (mysqli_query($link, $update_query)) {
        header("Location: admin_categories.php");
        exit();
    } else {
        echo "ERROR: Could not execute $update_query. " . mysqli_error($link);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Category - Electronix Store</title>
    <link rel="icon" href="../images/electronics.png" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <h1>Edit Category</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo htmlspecialchars($category['name']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</body>

</html>