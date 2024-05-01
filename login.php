<?php
if (isset($_POST['login-submit'])) {

$servername = "localhost";
$username = "root";
$password = "";
$database = "ecommerce";


$conn = mysqli_connect($servername, $username, $password, $database);

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    $usernameOrEmail = $_POST['username'];
    $password = $_POST['password'];


    if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM users WHERE email=?";
    } else {
        $sql = "SELECT * FROM users WHERE username=?";
    }
    
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: login.html?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $usernameOrEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {

            $passwordCheck = password_verify($password, $row['password']);
            if ($passwordCheck == false) {
                header("Location: login.html?error=wrongpassword");
                exit();
            } else if ($passwordCheck == true) {
                session_start();
                $_SESSION['userId'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("Location: index.php?login=success");
                exit();
            }
        } else {
            header("Location: login.php?error=nouser");
            exit();
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: login.php");
    exit();
}
?>
