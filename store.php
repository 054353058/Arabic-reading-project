<?php
include 'connect.php';
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// تحديد مجلد الحفظ
$uploadDirectory = 'uploads/';

// تأكيد أن الطلب هو POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // تأكيد أن هناك ملف صوتي في الطلب
    if (isset($_FILES['audio'])) {
        $audioFile = $_FILES['audio'];

        // تأكيد أن الملف تم تحميله بنجاح
        if ($audioFile['error'] === UPLOAD_ERR_OK) {
            // حفظ الملف في المجلد المحدد
            $uploadPath =  $uploadDirectory . basename($audioFile['name']);
            move_uploaded_file($audioFile['tmp_name'], $uploadPath);
            $sql = $con->prepare("INSERT INTO participations (record_path, user_id, text_id) VALUES (?, ?, ?)");
            $sql->execute(array($uploadPath, $_SESSION['userid'], $_POST['textid']));
        } else {
            echo 'فشل في تحميل الملف.';
        }
    } else {
        echo 'لم يتم تقديم أي ملف صوتي.';
    }
} else {
    echo 'الطلب غير مسموح به.';
}
?>
