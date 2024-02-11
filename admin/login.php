<?php 
    session_start();
    if(!empty($_SESSION['adminid'])) {
        header("Location:index.php");
        exit;
    }
    include '../connect.php';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = htmlspecialchars(strip_tags($_POST['email']));
        $password = $_POST['password'];

        $sql = $con->prepare("SELECT * FROM admin_login WHERE email = ?");

        $sql->execute(array($email)); 
        $check = $sql->rowCount();
        if(!$check) {
            $_SESSION['msgClass'] = "show-msg error";
        }else {
            $userinfo = $sql->fetchAll()[0];
            if(password_verify($password, $userinfo['password'])) {
                $_SESSION['adminid'] = $userinfo['id'];
                header("Location: index.php");
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
        <p class="msg <?php echo $_SESSION['msgClass'] ?>">
           كلمة المرور او البريد الإلكتروني غير صحيح !
            <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
        </p>
        <?php $_SESSION['msgClass'] = '' ?>
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
        </form>
    </div>
<?php include 'footer.php'?>