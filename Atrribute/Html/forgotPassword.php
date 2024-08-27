<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/login.css">
    <title>Forgor</title>
</head>
    <body>
        <div class = "main">
            <div class = "box">
            <h1> Forgot password? </h1> 
                <form method = "post">
                    <div class = "email"  method = "post">
                        <input type = "email" name = "email" id = "email" placeholder = "email">
                    </div>
                        <button> send</button>
                </form>
            </div>  
        </div>

    </body>
</html>   
<?php
    $conn = new mysqli("sql205.infinityfree.com","if0_36786623","Putlocker21","if0_36786623_db_schema");

    $email = $_POST["email"];

    $token = bin2hex(random_bytes(16)); 
    $token_hash = hash("sha256",$token);

    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);


    $sql = "UPDATE Users  
            SET 
                reset_token_hash = ?,
                reset_token_expires_at = ?
            WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss",$token_hash,$expiry,$email);
    $stmt->execute();

    if ($conn->affected_rows) {
        $mail = require __DIR__ . "/html/mailer.php";
     
        $mail->setFrom("noreply@attributewindow.42web.io");
        $mail->addAddress($email);
        $mail->Subject = "Password Reset";
        $mail->Body = <<<END
        Click <a href="https://attributewindow.42web.io/html/reset.php?token=$token">here</a> to reset 
        your password.
        END;
     
        try {
            $mail->send();
            echo "Password reset email sent.";
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
     }
?> 