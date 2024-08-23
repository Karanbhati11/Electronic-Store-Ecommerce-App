<?php
require_once 'fpdf184/fpdf.php';
require_once __DIR__ . '../includes/db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

global $link;
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    die('Order ID is required.');
}

// Fetch order details
$order_query = mysqli_query($link, "
    SELECT o.id AS order_id, o.order_date AS orderDate, o.total AS totalAmount, 
           CONCAT(u.username) AS customerName, u.email AS customerEmail, 
           o.shipping_name AS shippingName, o.shipping_address AS shippingAddress, 
           o.shipping_city AS shippingCity, o.shipping_state AS shippingState, 
           o.shipping_zip AS shippingZip, o.shipping_phone AS shippingPhone 
    FROM Orders o 
    JOIN Users u ON o.user_id = u.id 
    WHERE o.id = $order_id
");

if ($order_query && mysqli_num_rows($order_query) > 0) {
    $order = mysqli_fetch_assoc($order_query);

    // Fetch order items
    $items_query = mysqli_query($link, "
        SELECT p.name AS productName, oi.quantity AS quantity, oi.price AS unitPrice, 
               (oi.quantity * oi.price) AS totalPrice 
        FROM OrderItems oi 
        JOIN Products p ON oi.product_id = p.id 
        WHERE oi.order_id = $order_id
    ");

    if ($items_query && mysqli_num_rows($items_query) > 0) {
        $pdf = new FPDF();
        $pdf->AddPage();

        // Set company logo and title
        $pdf->Image('./images/logo.png', 5, 5, 20);
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(0, 10, 'Electronix Store', 0, 1, 'C');
        $pdf->SetFont('Arial', 'I', 14);
        $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
        $pdf->Ln(10);

        // Order and Customer Information
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Order ID: " . $order["order_id"], 0, 1);
        $pdf->Cell(0, 10, "Order Date: " . date('d-m-Y', strtotime($order["orderDate"])), 0, 1);
        $pdf->Ln(5);

        $pdf->Cell(0, 10, "Customer Name: " . $order["customerName"], 0, 1);
        $pdf->Cell(0, 10, "Customer Email: " . $order["customerEmail"], 0, 1);
        $pdf->Ln(10);

        // Shipping Information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Shipping Details", 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $order["shippingName"], 0, 1);
        $pdf->Cell(0, 10, $order["shippingAddress"], 0, 1);
        $pdf->Cell(0, 10, $order["shippingCity"] . ", " . $order["shippingState"] . " " . $order["shippingZip"], 0, 1);
        $pdf->Cell(0, 10, "Phone: " . $order["shippingPhone"], 0, 1);
        $pdf->Ln(10);

        // Products List
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Purchased Items:", 0, 1);
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        while ($item = mysqli_fetch_assoc($items_query)) {
            $pdf->Cell(0, 8, $item["productName"], 0, 1);
            $pdf->Cell(0, 8, "Quantity: " . $item["quantity"] . " | Unit Price: $" . number_format($item["unitPrice"], 2), 0, 1);
            $pdf->Cell(0, 8, "Total: $" . number_format($item["totalPrice"], 2), 0, 1);
            $pdf->Ln(5);
        }

        $pdf->Ln(10);

        // Grand Total
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, "Grand Total: $" . number_format($order["totalAmount"], 2), 0, 1, 'R');

        // Footer
        $pdf->Ln(20);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, "Thank you for shopping with Electronix Store!", 0, 1, 'C');

        // Set headers to force download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="invoice.pdf"');

        // Output the PDF
        $pdf->Output('D', 'invoice.pdf');
    } else {
        echo "No items found for this order.";
    }
} else {
    echo "Order not found.";
}

// Close connection
mysqli_close($link);
?>