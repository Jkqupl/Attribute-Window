<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/add.css">
    <title>Log in</title>
</head>
<body>


    <div class="main">
        <div class = "back">
        <a href = "/html/window.php"> Back</a>
        </div>

        <div class="add">
            <h1>Enter your Stat!!</h1>
            <form action="" method="post" id = "addF">
                <div class="stat">
                    <input type="text" id="stat" name="stat" placeholder="Enter your Stat e.g 'Exercise'">
                </div>
                <div class="sub">
                    <button type="submit" id="sub">Add</button>  
                </div>
            </form>
        </div>


        <div class="upgrade">
            <h1>Upgrade a stat</h1>
            <div class="text">
                <?php
                $conn = new mysqli("sql205.infinityfree.com","if0_36786623","Putlocker21","if0_36786623_db_schema");
                $id = $_SESSION["id"];
                
                $userData = "SELECT * FROM Stats WHERE userId = '$id'";
                $result = mysqli_query($conn, $userData);
                
                while($row = mysqli_fetch_assoc($result)){

                //getting the points from the attributes table and displaying them

                    $statId = $row["id"];
                    $getPoint = "SELECT points FROM Attributes where statId = '$statId' ";
                    $points = mysqli_query($conn,$getPoint);
                    $GetAttributeRow = mysqli_fetch_assoc($points);
                    $number = $GetAttributeRow["points"];

                    echo '<div class="l">';
                    echo "[" . strtoupper($row["text"]) . " (" . $number . "/7) " ." LV." . $row["level"] . "], ";
                    echo '<form action="" method="POST">';
                    echo '<input type="hidden" name="statId" value="' . $row["id"] . '">';
                    echo '<input type="submit" name="upgrade" value="Upgrade">';
                    echo '</form>';
                    echo '</div>';
                }


                $conn->close();
                ?>
            </div>
        </div>

        <!-- Removing stat from the stat table and the Attributes table -->

        <div class="remove">
            <h1>Remove a stat</h1>
            <div class="text">
                <?php
                $conn = new mysqli("sql205.infinityfree.com","if0_36786623","Putlocker21","if0_36786623_db_schema");
                $id = $_SESSION["id"];
                
                $userData = "SELECT * FROM Stats WHERE userId = '$id'";
                $result = mysqli_query($conn, $userData);
                
                while($row = mysqli_fetch_assoc($result)){

                //getting the points from the attributes table
                    $statId = $row["id"];
                    $getPoint = "SELECT points FROM Attributes where statId = '$statId' ";
                    $points = mysqli_query($conn,$getPoint);
                    $GetAttributeRow = mysqli_fetch_assoc($points);
                    $number = $GetAttributeRow["points"];

                    echo '<div class="s">';
                    echo "[" . strtoupper($row["text"]) . " (" . $number . "/7) " . " LV." . $row["level"] . "], ";
                    echo '<form action="" method="POST">';
                    echo '<input type="hidden" name="statId" value="' . $row["id"] . '">';
                    echo '<input type="submit" name="delete" value="Delete">';
                    echo '</form>';
                    echo '</div>';

                }

//This deletes from database
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
                    $statId = $_POST["statId"];
                    $deleteStat = "DELETE FROM Stats WHERE id='$statId'";
                    $deleteAttribute = "DELETE FROM Attributes WHERE statId = '$statId' ";
                    mysqli_query($conn, $deleteStat);
                    mysqli_query($conn, $deleteAttribute);
                    echo "<script>window.location.href=' /html/add.php '</script>";
                    exit();
                }

//This adds points to stat
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upgrade"])) {
                    
                    $statId = $_POST["statId"];

                    $GetAttribute = "SELECT points FROM Attributes WHERE statId = '$statId' ";
                    $AttributeResult = mysqli_query($conn,$GetAttribute);

                    $GetAttributeRow = mysqli_fetch_assoc($AttributeResult);
                    $AddPoint = "UPDATE Attributes SET points = points + 1 where statId = '$statId'";
                    $point = $GetAttributeRow["points"];
                   
                    if(fmod($point,7) == 0 ){
                        $updateStat = "UPDATE Stats SET level = level + 1 where id = '$statId'";
                        $updatePoint = "UPDATE Attributes SET points = 1 where statId = '$statId'";
                        if(mysqli_query($conn,$updateStat) && mysqli_query($conn,$updatePoint)){
                            echo "<script>window.location.href=' /html/add.php '</script>";
                             exit();
                        }

                    }else{
                        if(mysqli_query($conn, $AddPoint)){
                            echo "<script>window.location.href=' /html/add.php '</script>";
                            exit();
                        }
                    }
                    

                    exit();
                }

                $conn->close();
                ?>
            </div>
        </div>

    </div>
</body>
</html>


<!-- Adds stat to stat table and attribute table -->
<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["stat"])) {
    $conn = new mysqli("sql205.infinityfree.com","if0_36786623","Putlocker21","if0_36786623_db_schema");
    $id = $_SESSION["id"];
    $stat = $_POST["stat"];
    $level = 1;
    $sql = "INSERT INTO Stats (`userId`, `level`, `text`) VALUES ('$id', '$level', '$stat')";

    if (mysqli_query($conn, $sql)) {
        $statID = mysqli_insert_id($conn);
        $_SESSION["statId"] = $statID;
        $points = 1;
        $attributes = "INSERT INTO Attributes (`statId`, `points`) VALUES ('$statID', '$points')";

        if (mysqli_query($conn, $attributes)) {
            header("Location: /html/window.php");
            exit();
        }
    }

    $conn->close();
}
?>
