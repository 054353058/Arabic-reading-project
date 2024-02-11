<?php 
    session_start();
    if(!empty($_SESSION['userid'])) {
        header("Location:texts.php");
        exit;
    }
    include 'connect.php';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = htmlspecialchars(strip_tags($_POST['email']));
        $password = $_POST['password'];

        $sql = $con->prepare("SELECT * FROM users WHERE email = ?");

        $sql->execute(array($email)); 
        $check = $sql->rowCount();
        if(!$check) {
            $_SESSION['msgClass'] = "show-msg error";
        }else {
            $userinfo = $sql->fetchAll()[0];
            if(password_verify($password, $userinfo['password'])) {
                $_SESSION['userid'] = $userinfo['id'];
                header("Location: texts.php");
                exit;
            }else {
                $_SESSION['msgClass'] = "show-msg error";
            }
        }
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Login</title>
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main-content login-content">
        <?php if(!empty($_SESSION['msgClass'])):?>
            <p class="msg <?php echo $_SESSION['msgClass'] ?>">
                 كلمة المرور او البريد الإلكتروني غير صحيح !
                <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
            </p>
            <?php $_SESSION['msgClass'] = '' ?>
        <?php endif?>
        <form class="login-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
            <h2>تسجيل دخول</h2>
            <div class="input-container">
                <label for="email">البريد الالكتروني</label>
                <input type="text" id="email" name="email" required autocomplete="off" placeholder="بريد الإكتروني">
            </div>
            <div class="input-container">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required placeholder="كلمة المرور">
            </div>
        
            <input type="submit" name="submit" value="تسجيل">
            <p>ليس لديك حساب؟ <a href="signup.php"> إنشاء حساب الآن.</a></p>
        </form>
    </div>
<?php include 'footer.php'?>