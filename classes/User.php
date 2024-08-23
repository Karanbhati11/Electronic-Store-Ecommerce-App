<?php
require_once __DIR__ . '/../includes/db.php';

class User
{
    public static function register($username, $email, $password)
    {
        global $link;
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $query)) {
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
            if (mysqli_stmt_execute($stmt)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public static function login($username, $password)
    {
        global $link;
        $query = "SELECT id, password, role FROM Users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            return ['id' => $id, 'role' => $role];
                        }
                    }
                }
            }
        }
        return false;
    }
}
?>