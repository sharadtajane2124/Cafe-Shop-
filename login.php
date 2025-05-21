<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $_SESSION['user_id'] = $row['id'];
        header('location:home.php');
    } else {
        $message[] = 'Incorrect username or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">
    <form action="" method="post">
        <h3>User Login</h3>
        <select id="role-switch" onchange="switchLogin()">
            <option value="user" selected>User</option>
            <option value="employee">Employee</option>
            <option value="admin">Admin</option>
        </select>
        <input type="email" name="email" required placeholder="Enter your email" class="box">
        <input type="password" name="pass" required placeholder="Enter your password" class="box">
        <input type="submit" value="Login Now" name="submit" class="btn">
        <p>Don't have an account? <a href="register.php">Register now</a></p>
    </form>
</section>

<script>
function switchLogin() {
    let role = document.getElementById("role-switch").value;
    if (role === "employee") {
        window.location.href = "employee/employee_login.php";
    } else if (role === "admin") {
        window.location.href = "admin/admin_login.php";
    }
}
</script>

</body>
</html>
