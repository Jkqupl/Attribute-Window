<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/signUp.css">
    <title>Sign Up</title>
</head>
<body>
    <div class = "main">
        <div class = "box">
            <h1>Register!!</h1>
            <form action = "" method="post">
                <div class = "email">
                    <input type = "email" id = "email" name = "email" placeholder = "email">
                </div>
                <div class = "name">
                    <input type = "text" id = "name" name = "name" placeholder="name">
                </div>
                <div class = "uname">
                    <input type = "text" id = "uname" name = "uname" placeholder="Username">
                </div>
                <div class = "age">
                    <input type = "number" id = "age" name = "age" placeholder="age">
                </div>
                <div class = "pass">
                    <input type = "password" id="pass" name = "password" placeholder="passwords">
                </div>
                <div class = "sub">
                    <button type = "submit" id = "sub"> SignUp</button>  
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php 

$conn = new mysqli("sql205.infinityfree.com","if0_36786623","Putlocker21","if0_36786623_db_schema");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["uname"]) && isset($_POST["password"])) {

    $name = $_POST["name"];
    $uname = $_POST["uname"];
    $age = $_POST["age"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $Epass = password_hash($password, PASSWORD_DEFAULT);

    $result = mysqli_query($conn,"SELECT * FROM Users WHERE username = '$uname'" );
    if ($result && mysqli_num_rows($result) > 0) {
        echo '<script>alert("Username already taken")</script>';
    } else {
        $sql = "INSERT INTO Users (`name`, `username`, `age`, `password`, `email`) VALUES ('$name', '$uname', '$age', '$Epass','$email')";
        if (mysqli_query($conn, $sql)) {
            header("Location: /index.html");
            exit();
        } else {
            echo '<script>alert("Error: Unable to register user")</script>';
        }
    }
    $conn->close(); 
}
?>