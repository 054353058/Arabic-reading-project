<?php 
    include 'header.php';
    include '../connect.php';
?>

    <div class="main-content">
        <h1 class="main-title">مرحبًا بعودتك.</h1>
        <div class="statics">
            <a href="participations.php">
                <h3><?php echo howMany('participations') ?> <i class="fa-solid fa-paper-plane"></i></h3>
                <p>عدد المشاركات </p>
            </a>
            <a href="texts.php">
                <h3><?php echo howMany('texts') ?> <i class="fa-solid fa-file-lines"></i></h3>
                <p>عدد النصوص</p>
            </a>
        </div>
    </div>
<?php include 'footer.php'?>