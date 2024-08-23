<?php

// Check if the user is logged in
$is_logged_in = isset($_SESSION['id']);

// Check if the user is an admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/Electronic%20Store/index.php">Electronix Store</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">

            <?php if ($is_admin): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/Electronic%20Store/admin/admin_products.php">Manage Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Electronic%20Store/admin/admin_categories.php">Manage Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Electronic%20Store/logout.php">Logout</a>
                </li>
            <?php elseif ($is_logged_in): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/Electronic%20Store/products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Electronic%20Store/cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Electronic%20Store/logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/Electronic%20Store/login.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>