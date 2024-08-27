<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/window.css">
    <title>Window</title>
</head>
<body>
    <div class="main">
        <div class="menu">
            <!-- <a href = "/index.html">home</a> -->
            <a href = "/php/logout.php"> Logout</a>
        </div>
        <div class="everythingElse">
            <div class="title">
                <p>>CHARACTER PROFILE<</p>
            </div>
            <div class="text">
                <div class = "name">
                    <?php 
                    echo  "NAME: " . strtoupper($_SESSION["name"]) ; 
                    ?> 
                </div>
                <br>
                <div class = "age">
                    <?php
                    echo "AGE: " . $_SESSION["age"];
                    ?>
                </div>
                <br>
                <div class = "stats"> 
                    <?php
                    echo "STATS: ";
                    $conn = new mysqli("sql205.infinityfree.com","if0_36786623","Putlocker21","if0_36786623_db_schema");
                    $id = $_SESSION["id"];
                    
                    $userData = "SELECT * FROM Stats where $id = userId ";
                    $result = mysqli_query($conn,$userData);

                    while($row = mysqli_fetch_assoc($result)){
                        //getting the points from the attributes table
                            $statId = $row["id"];
                            $getPoint = "SELECT points FROM Attributes where statId = '$statId' ";
                            $points = mysqli_query($conn,$getPoint);
                            $GetAttributeRow = mysqli_fetch_assoc($points);
                            $number = $GetAttributeRow["points"];

                            echo "<div class = s>";
                            echo "[" . strtoupper($row["text"]) . " (" . $number . "/7) " . " LV.". $row["level"] . "], ";
                            echo "</div>";
                    }
                    echo "<a href = /html/add.php> Add/Remove/Upgrade </a> ";
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>