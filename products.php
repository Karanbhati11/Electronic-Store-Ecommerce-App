<?php
require_once 'classes/Product.php';
session_start();

// Fetch all products
$products = Product::getAllProducts();

// Fetch all categories
$categories = Product::getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products - Electronix Store</title>
    <link rel="icon" href="./images/electronics.png" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/styles.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #ffffff;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Sidebar Filters */
        .filter-sidebar {
            background-color: #f8f9fa;
            border-radius: 3px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .filter-sidebar h4 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            font-weight: 700;
            color: #86a194;
        }

        .filter-sidebar .form-group {
            margin-bottom: 15px;
        }

        .filter-sidebar label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .product-card {
            background-color: #ffffff;
            border-radius: 3px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-in-out;
            text-align: center;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .product-card .card-body {
            padding: 15px;
        }

        .product-card .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #86a194;
        }

        .product-card .card-text {
            color: #555;
            margin-bottom: 15px;
        }

        .product-card .btn {
            background-color: #86a194;
            border-color: #86a194;
            color: #ffffff;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 3px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .product-card .btn:hover {
            background-color: #6f867c;
            border-color: #6f867c;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include("navbar.php") ?>

    <div class="container">
        <div class="row">
            <!-- Sidebar for Filters -->
            <div class="col-md-3">
                <div class="filter-sidebar">
                    <h4>Filters</h4>

                    <!-- Filter by Category -->
                    <div class="form-group">
                        <label for="categoryFilter">Category</label>
                        <select id="categoryFilter" class="form-control">
                            <option value="all">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter by Price -->
                    <div class="form-group">
                        <label for="priceRange">Price Range</label>
                        <input type="range" id="priceRange" class="form-control-range" min="0" max="1000" step="10"
                            value="1000">
                        <div class="d-flex justify-content-between mt-2">
                            <span>$0</span>
                            <span id="priceRangeValue">$1000</span>
                        </div>
                    </div>

                    <!-- Filter by Search -->
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" id="search" class="form-control" placeholder="Search products...">
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-md-9">
                <div class="product-grid" id="product-list">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-card" data-category="<?php echo htmlspecialchars($product['category_id']); ?>"
                                data-price="<?php echo htmlspecialchars($product['price']); ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-description="<?php echo htmlspecialchars($product['description']); ?>">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text">Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                                    <a href="product.php?id=<?php echo htmlspecialchars($product['id']); ?>"
                                        class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No products available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Update the price range display
            $('#priceRange').on('input', function () {
                $('#priceRangeValue').text('$' + $(this).val());
                filterProducts();
            });

            // Filter by category
            $('#categoryFilter').on('change', function () {
                filterProducts();
            });

            // Filter by search term
            $('#search').on('keyup', function () {
                filterProducts();
            });

            // Function to filter products
            function filterProducts() {
                var selectedCategory = $('#categoryFilter').val();
                var selectedPrice = $('#priceRange').val();
                var searchTerm = $('#search').val().toLowerCase();

                $('.product-card').each(function () {
                    var category = $(this).data('category');
                    var price = $(this).data('price');
                    var name = $(this).data('name').toLowerCase();


                    var matchesCategory = (selectedCategory === 'all' || category == selectedCategory);
                    var matchesPrice = (parseFloat(price) <= parseFloat(selectedPrice));
                    var matchesSearch = (name.includes(searchTerm));

                    if (matchesCategory && matchesPrice && matchesSearch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
            filterProducts();
        });
    </script>

    <?php include("footer.php") ?>

</body>

</html>