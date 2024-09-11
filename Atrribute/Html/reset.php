<?php

$token = $_GET["token"];
$token_hash = hash("sha256", $token);
$conn = new mysqli("sql205.infinityfree.com", "if0_36786623", "Putlocker21", "if0_36786623_db_schema");

$sql = "SELECT * FROM Users WHERE reset_token_hash = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user == null) {
    die("Token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}


// Handling form submission for password reset
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["passConfirm"]) && isset($_POST["password"])) {
    $password = $_POST["password"];
    $passConfirm = $_POST["passConfirm"];

    // Check if password and password confirmation match
    if ($password === $passConfirm) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sqli = "UPDATE Users 
                    SET `password`= ?, 
                        `reset_token_hash` = NULL, 
                        `reset_token_expires_at` = NULL
                    WHERE id = ?";
        $stmti = $conn->prepare($sqli);
        $stmti->bind_param("ss", $password_hash, $user["id"]);
        $stmti->execute();

        if ($stmti->affected_rows > 0) {
            // Redirect to homepage after successful update
            header("Location: /");
            exit();  // Make sure to exit after the header redirect
        } else {
            echo "Failed to update password.";
        }
    } else {
        echo "Passwords do not match!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/reset.css">
    <title>Reset</title>
</head>
<body>
    <div class="main">
        <div class="box">
        <div class = "txt">
            <?php
            echo "Token is valid and hasn't expired";
            ?>
        </div>
            <h1>Reset Password!!</h1>
            <form action="" method="post">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>"> 
                <div class="pass">
                    <input type="password" id="pass" name="password" placeholder="Password">
                </div>
                <div class="passConfirm">
                    <input type="password" id="passConfirm" name="passConfirm" placeholder="Repeat password">
                </div>
                <div class="sub">
                    <button type="submit" id="sub">Change Password</button>  
                </div>
            </form>
        </div>
    </div>
</body>
</html>