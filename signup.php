<?php 
    session_start();

    if(!empty($_SESSION['userid'])) {
        header("Location:texts.php");
        exit;
    }
    
    include 'connect.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $errors = array();

        $fname = htmlspecialchars(strip_tags($_POST['fname']));
        $lname = htmlspecialchars(strip_tags($_POST['lname']));
        $birthDay = htmlspecialchars(strip_tags($_POST['birth_day']));
        $region = htmlspecialchars(strip_tags($_POST['region']));
        $email = htmlspecialchars(strip_tags($_POST['email']));
        $password = $_POST['password'];
        $hasedPass = password_hash($password, PASSWORD_DEFAULT);

        $splited = explode('-', $birthDay);

        if(strlen($fname) < 3) {
            $errors['fname'] = ' الإسم لا يمكن ان يكون اقل من 3 حروف';
        }
        if(strlen($lname) < 3) {
            $errors['lname'] = ' الإسم لا يمكن ان يكون اقل من 3 حروف';
        }
        if(empty($birthDay)) {
            $errors['birth_day'] = 'عذرا، يجب إدخال التاريخ';

        }elseif(!checkdate($splited[1], $splited[2], $splited[0])) {

            $errors['birth_day'] = 'عذرا، يرجى إدخال تاريخ صالح';
        }
        if(strlen($password) < 8) {
            $errors['password'] =' كلمة المرور لا يمكن ان تكون اقل من 8 حروف';
        }
        if(empty($region)) {
            $errors['region'] = 'عذرا، يجب كتابة المنطقة';

        }
        if(empty($email)) {
            $errors['email'] = 'عذرا، يجب كتابة البريد الإلكتروني';
            
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'عذرا، يجب كتابة بريد إلكتروني صالح';
        }

        

        if(empty($errors)) {
            $check = $con->prepare("SELECT email FROM users WHERE email = ?");
            $check->execute([$email]);
            $result = $check->rowCount();

            
            if($result == 0) {
                $sql = $con->prepare('INSERT INTO users (name, email, password, country, birth_day) 
                VALUES (?, ?, ?, ?, ?)');
                $sql->execute(array($fname . " " . $lname, $email, $hasedPass, $region, $birthDay));
                    $_SESSION['userid'] = $con->lastInsertId();

                    $_SESSION['msgClass'] = 'show-msg success';

                    header("Refresh:1; url=texts.php");
            }else {
                $_SESSION['msgClass'] = 'show-msg error';
            }
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>إنشاء حساب جديد</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,700;0,900;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-body">

<div class="login-container">
    <h2>إنشاء حساب</h2>
 
    <?php if(!empty($_SESSION['msgClass']) && $_SESSION['msgClass'] != 'show-msg error') {?>
        <p class="msg <?php echo $_SESSION['msgClass'] ?>">
            تم إنشاء الحساب بنجاح
            <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
        </p>
        <?php $_SESSION['msgClass']  = ''?>
    <?php }else {?>
        <p class="msg <?php echo $_SESSION['msgClass'] ?>">
            البريد الإلكتروني مستخدم، حاول ببريد إلكتروني مختلف.
            <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
        </p>
        <?php $_SESSION['msgClass']  = ''?>
    <?php }?>
    <form class="login-form" action="" method="post" onsubmit="inputsError()">
        <div class="input-row">
      
            <div class="input-container">
                <label for="name">الإسم الاول</label>
                <input type="text" id="fname"  name="fname"  value="<?php echo isset($_POST['fname']) && $_POST['fname'] != '' ? $_POST['fname'] : ''?>">
                <?php if (isset($errors['fname'])) echo '<p class="input-error show-msg">' . $errors['fname'] . '</p>'; ?>
            </div>

            <div class="input-container">
                <label for="region">الاسم الأخير </label>
                <input type="text" id="lname"  name="lname"  value="<?php echo isset($_POST['lname']) && $_POST['lname'] != '' ? $_POST['lname'] : ''?>">
                <?php if (isset($errors['lname'])) echo '<p class="input-error show-msg">' . $errors['lname'] . '</p>'; ?>
            </div>
            
        </div>
     
        <div class="input-row">
         

            <div class="input-container">
            <label for="password">كلمة المرور</label>

            <input type="password" id="password"  name="password" >
            <?php if (isset($errors['password'])) echo '<p class="input-error show-msg">' . $errors['password'] . '</p>'; ?>

            </div>

            <div class="input-container">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email"  name="email"  value="<?php echo isset($_POST['email']) && $_POST['email'] != '' ? $_POST['email'] : ''?>">
            <?php if (isset($errors['email'])) echo '<p class="input-error show-msg">' . $errors['email'] . '</p>'; ?>
            </div>
        </div>
      
        <div class="input-row">

            <div class="input-container">

                <label for="region">المنطقة</label>
                <input type="text" id="region"  name="region"  value="<?php echo isset($_POST['region']) && $_POST['region'] != '' ? $_POST['region'] : ''?>">
                <?php if (isset($errors['region'])) echo '<p class="input-error show-msg">' . $errors['region'] . '</p>'; ?>
            </div>
        
            <div class="input-container">
                <label for="birth_day">تاريخ الميلاد</label>
                <input type="date" id="birth_day"  name="birth_day"  value="<?php echo isset($_POST['birth_day']) && $_POST['birth_day'] != '' ? $_POST['birth_day'] : ''?>">
                <?php if (isset($errors['birth_day'])) echo '<p class="input-error show-msg">' . $errors['birth_day'] . '</p>'; ?>
                
            </div>
        </div>
        <input type="submit" name="signup" value="إنشاء">
        <p>إذا كنت قد أنشأت حسابًا من قبل، <a href="login.php">قم بتسجيل الدخول</a>.</p>
    </form>
</div>

<?php include 'footer.php'?>
