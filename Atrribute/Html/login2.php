<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/login.css">
    <title>Log in</title>
</head>
<body>
    <div class = "main">
        <div class = "box">
            <h1>Sign in!!</h1>
            <form action = "" method="post">
                <div class = "name">
                    <input type = "text" id = "uname" name = "uname" placeholder="Username">
                </div>
                <div class = "pass">
                    <input type = "password" id="pass" name = "pass" placeholder="password">
                </div>
                <div class = "sub">
                    <button type = "submit" id = "sub"> LogIn</button>  
                </div>
                <a href ="forgotPassword.php">Forgot password? </a>
            </form>
        </div>
    </div>
</body>
</html>

<?php 
session_start();

$conn = new mysqli("sql205.infinityfree.com","if0_36786623","Putlocker21","if0_36786623_db_schema");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["uname"]) && isset($_POST["pass"])) {
   
    $query = "SELECT * FROM Users WHERE username = ? "; 

    $uname = $_POST["uname"];
    $password = $_POST["pass"];

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $uname);

    // Execute the statement
    $stmt->execute();

    $result = $stmt->get_result();


    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Verify the password
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["age"] = $row["age"];
            $_SESSION["email"] = $row["email"];
            header("Location: /html/window.php");
            exit();
        } else {
            // Password doesn't match
            echo '<script>alert("Password does not match")</script>';

            exit();
        }
    } else {
        // User not found
        echo '<script>alert("User not found")</script>';

        // header("Location: /html/login.html");
        exit();
    }
}


?>