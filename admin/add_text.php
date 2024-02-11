<?php 
    include '../connect.php';
    include 'header.php';
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $category = $_POST['category'];
        $status = $_POST['status'];
        $content = $_POST['content'];

        $sql = $con->prepare("INSERT INTO texts (content, status, category) VALUES (?, ?, ?)");
        $sql->execute(array($content, $status, $category)); 
        $_SESSION['msgClass'] = 'show-msg success';
    }
?>


<div class="add_text">
    <div class="main-content">
        <p class="msg <?php echo $_SESSION['msgClass'] ?>">
            تم الإضافة بنجاح
            <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
        </p>
        <?php $_SESSION['msgClass'] = '' ?>
        <div class="add-form">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <h3>إضافة نص جديد</h3>
                <div>
                    <label>التصنيف</label>
                    <input type="text" name="category" placeholder="اكتب تصنيف النص" required>
                </div>
                <div>
                    <label for="status">الحالة</label>
                    <select name="status">
                        <option value="1">إظهار</option>
                        <option value="0">إخفاء</option>
                    </select>
                </div>
                <div>
                    <label>المحتوى</label>
                    <textarea name="content" placeholder="اكتب محتوى الرسالة......" required></textarea>
                </div>
                <input type="submit" value="إضافة">
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'?>