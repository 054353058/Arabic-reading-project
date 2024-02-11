<?php 
    session_start();
    if(!isset($_SESSION['userid'])) {
        header('Location: login.php');
        exit;
    }
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <title>تسجيل دخول</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,700;0,900;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/style.css?date=<?php echo time() ?>">
</head>
<body>

<div class="navbar">

    <div class="navbar-right">
        <span class="author-name">مقرأة التفاعلية</span>
        <span class="supervisor">بأشراف د. عبدالكريم الزهراني</span> 
    </div>

    <div class="navbar-left">
        <a href="texts.php" data-link="texts.php" class="active">النصوص</a>
        <a href="participations.php" data-link="participations.php">مشاركاتي</a>
        <a href="profile.php" data-link="profile.php">حسابي</a>
        <a href="logout.php" class="logout-button">تسجيل الخروج</a>
    </div>
    
</div>
