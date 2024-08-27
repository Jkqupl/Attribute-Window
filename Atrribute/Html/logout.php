<?php

$conn = new mysqli("sql205.infinityfree.com","if0_36786623","Putlocker21","if0_36786623_db_schema");

session_unset();
session_destroy();
header("Location: /index.html");
?>