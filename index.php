<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="CSS/index.css">
  <link rel="icon" href="./images/electronics.png" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="content">
    <div class="hero-section">
      <h1>Welcome to Electronix Store</h1>
    </div>

    <div class="container product-highlights">
      <h2>Featured Products</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="card product-card">
            <img src="images/s21.webp" class="card-img-top" alt="Samsung Galaxy S21">
            <div class="card-body">
              <h5 class="card-title">Samsung Galaxy S21</h5>
              <p class="card-text">High-end performance with an advanced camera system and sleek design.</p>
              <a href="product.php?id=1" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card product-card">
            <img src="images/xps13.webp" class="card-img-top" alt="Dell XPS 13">
            <div class="card-body">
              <h5 class="card-title">Dell XPS 13</h5>
              <p class="card-text">High-performance laptop with a stunning 13-inch display.</p>
              <a href="product.php?id=4" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card product-card">
            <img src="images/sony_wh1000xm4.webp" class="card-img-top" alt="Sony WH-1000XM4">
            <div class="card-body">
              <h5 class="card-title">Sony WH-1000XM4</h5>
              <p class="card-text">Industry-leading noise cancellation and exceptional sound quality.</p>
              <a href="product.php?id=3" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.php'); ?>

</body>

</html>