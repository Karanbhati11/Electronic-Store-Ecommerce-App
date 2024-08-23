<?php
require_once __DIR__ . '/../includes/db.php';

class Order
{
    public static function createOrder($user_id, $total, $shipping_details)
    {
        global $link;

        $query = "INSERT INTO Orders (user_id, total, shipping_name, shipping_address, shipping_city, shipping_state, shipping_zip, shipping_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $query)) {
            $name = $shipping_details['name'];
            $address = $shipping_details['address'];
            $city = $shipping_details['city'];
            $state = $shipping_details['state'];
            $zip = $shipping_details['zip'];
            $phone = $shipping_details['phone'];

            mysqli_stmt_bind_param($stmt, "idssssss", $user_id, $total, $name, $address, $city, $state, $zip, $phone);

            if (mysqli_stmt_execute($stmt)) {
                return mysqli_insert_id($link);
            } else {
                echo "Error executing statement: " . mysqli_stmt_error($stmt) . "<br>";
            }
        } else {
            echo "Error preparing statement: " . mysqli_error($link) . "<br>";
        }
        return false;
    }

    public static function addOrderItem($order_id, $product_id, $quantity, $price)
    {
        global $link;

        $query = "INSERT INTO OrderItems (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $query)) {
            mysqli_stmt_bind_param($stmt, "iiid", $order_id, $product_id, $quantity, $price);

            if (mysqli_stmt_execute($stmt)) {
                return true;
            } else {
                echo "Error executing statement: " . mysqli_stmt_error($stmt) . "<br>";
            }
        } else {
            echo "Error preparing statement: " . mysqli_error($link) . "<br>";
        }
        return false;
    }
}
?>