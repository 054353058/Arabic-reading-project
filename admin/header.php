<?php 
    session_start();
    if(empty($_SESSION['adminid'])) {
        header("Location:login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h2 class="logo">د.عبد الكريم الزهراني</h2>
        <ul class="nav-item">
            <li><a href="index.php" data-link="index.php" class="active">الرئيسية</a></li>
            <li><a href="participations.php" data-link="participations.php">المشاركات</a></li>
            <li><a href="texts.php" data-link="texts.php">النصوص</a></li>
            <li><a href="logout.php" class="logout-btn">تسجيل الخروج</a></li>
        </ul>
    </header>