<?php 
    include 'connect.php';
    include 'header.php';

    if(!empty($_SESSION['userid'])) {
        $get = $con->prepare("SELECT * FROM users WHERE id = ?");
        $get->execute(array($_SESSION['userid']));
        $myData = $get->fetchAll()[0];
    }

?>



<div class="profile-container">
    <h1>الملف الشخصي</h1>
    <div class="user-info">
        <div class="user-detail">
            <strong>اسم المستخدم: </strong> <span id="userName"><?php echo $myData['name']?></span>
        </div>
        <div class="user-detail">
            <strong >البريد الإلكتروني: </strong> <span id="userEmail"><?php echo $myData['email']?></span>
        </div>
        <div class="user-detail">
            <strong>المنطقة: </strong> <span id="userRegion"><?php echo $myData['country']?></span>
        </div>
        <div class="user-detail">
            <strong >تاريخ الميلاد: </strong> <span id="userBirthday"><?php echo $myData['birth_day']?></span>
        </div>
       
        </div>
    </div>
</div>

<?php include 'footer.php'?>
