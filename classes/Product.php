<?php
require_once __DIR__ . '/../includes/db.php';

class Product
{
    public static function getAllProducts()
    {
        global $link;
        $query = "SELECT * FROM Products";
        $result = mysqli_query($link, $query);
        if (!$result) {
            die('Query failed: ' . mysqli_error($link));
        }
        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }


    public static function getAllCategories()
    {
        global $link;
        $query = "SELECT * FROM Categories";
        $result = mysqli_query($link, $query);

        if (!$result) {
            die('Query failed: ' . mysqli_error($link));
        }

        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }

        return $categories;
    }

    public static function getProductById($id)
    {
        global $link;
        $query = "SELECT * FROM Products WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $query)) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                return mysqli_fetch_assoc($result);
            }
        }
        return null;
    }
}
?>