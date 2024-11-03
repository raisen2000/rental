<?php
if (is_live_environment()) {
    $conn = new mysqli('localhost', 'u297599468_ohrmslpa', 'Ohrmslpa2024', 'u297599468_ohrmslpa_db') or die("Could not connect to mysql" . mysqli_error($con));
    echo "<script>console.log('live');</script>";
} else {
    $conn = new mysqli('localhost', 'root', '', 'house_rental_db') or die("Could not connect to mysql" . mysqli_error($con));
    echo "<script>console.log('local');</script>";
}

function is_live_environment()
{
    return $_SERVER['SERVER_NAME'] === 'ohrmslpa.site';
}
