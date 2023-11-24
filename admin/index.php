<?php
session_start();
include_once '../config.php';

if (isset($_POST['login'])) {
    if (empty($_POST['username'])) {
        $username_error = "<p style='color:red;padding:10px'>Username is required*</p>";
    } else {
        $username = mysqli_escape_string($conn, $_POST['username']);
    }

    if (empty($_POST['password'])) {
        $password_error = "<p style='color:red;padding:10px'>Username is required*</p>";
    } else {
        $password = mysqli_escape_string($conn, $_POST['password']);
    }

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $users = $result->fetch_assoc();
            $_SESSION['id']=$users["id"];
            $_SESSION['username']=$users["username"];

            $login_message = "<p style='background:green;color:white'>Logged in successfully</p>";
            header("Location: ./list.php");
        } else {
            $login_error = "<p style='background:red;color:white'>Wrong username or password</p>";
        }
    }
}

$conn->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>crud system</title>
</head>
<style>
* {
    box-sizing: border-box;

}

.btn {
    padding: 10px 10px;
    width: 30%;
    background: blue;
    color: white;
    border: none;
    border-radius: 5px;
}

.form-input {
    padding: 8px;
    width: 100%;
    border: none;
    outline: 1px solid blue;
    border-radius: 5px;
}

.form-label {
    padding: 5px 0;
}

.login-button {
    text-align: right;
    padding: 10px 0;
}
</style>

<body>
    <div style="width:50%;margin:auto;justify-items:center">
        <?php if(isset($login_message)){echo($login_message);}?>
        <?php if(isset($login_error)){echo($login_error);}?>
        <h3>Admin Login</h3>
        <form action="" method="post">
            <div style="padding-top:10px">
                <div style="padding:5px 0;" class="form-lable">
                    <label class="form-label" for="">Username</label>
                </div>
                <div class="form-input-group">
                    <input class="form-input" type="text" name="username" placeholder="Username" required>
                </div>
            </div>

            <div style="padding-top:10px">
                <div style="padding:5px 0;" class="form-lable">
                    <label class="form-label" for="">Password</label>
                </div>
                <div class="form-input-group">
                    <input class="form-input" required type="password" name="password" placeholder="********" required>
                </div>
            </div>

            <div class="login-button">
                <button class="btn" type="submit" name="login">Login</button>
            </div>
        </form>
    </div>
    </div>
</body>

</html>