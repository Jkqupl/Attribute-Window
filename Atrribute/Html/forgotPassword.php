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

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {

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
            //Import PHPMailer classes into the global namespace
            //These must be at the top of your script, not inside a function

            //Load Composer's autoloader
            require 'vendor/autoload.php';

            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'narutobob909@gmail.com';                     //SMTP username
                $mail->Password   = 'yozghsmrmfnbltaq';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('narutobob909@gmail.com', 'Mailer');
                $mail->addAddress($email, 'Joe User');     //Add a recipient
                

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'test';
                $mail->Body = <<<END
                Click <a href="https://attributewindow.42web.io/html/reset.php?token=$token">here</a> to reset 
                your password.
                END;
                $mail->send();
                echo 'Message has been sent';
                header("Location: /");

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            
            }
        }

        echo "Message has been sent";
?> 