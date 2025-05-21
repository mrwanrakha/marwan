<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password, firstname, lastname FROM users WHERE email = ? AND password = ?");
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("ss", $email,$password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
            // Setting session variables for user details
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            
            $stmt->close();
            echo "Success";
        $conn->close();
        header("Location: ../index.php");
        exit();
    }else {
        $_SESSION['error'] = 'Error!';
        $stmt->close();
        $conn->close();
        echo "Pass ghalat";
        header("Location: ../login.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'Connection error';
    $stmt->close();
    $conn->close();
    echo "Error gedann: ",$email , "password: ",$password;
    header("Location: ../login.php");
    exit();
}
?>
