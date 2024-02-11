<?php 
    include '../connect.php';
    include 'header.php';

    $checker = isset($_GET['text']) && $_GET['text'] != '' ? $_GET['text'] : '';

    if($checker == '' || $checker == 'all') { 

        $query = $con->prepare("SELECT * FROM texts"); 
        $query->execute(array());
        $texts = $query->fetchAll();
        
        ?>
            <div class="texts">
                <div class="main-content">
                    <p class="msg <?php echo $_SESSION['msgClass'] ?>">
                        تم الحذف بنجاح
                        <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
                    </p>
                    <?php $_SESSION['msgClass'] = '' ?>
                    <h1 class="main-title">النصوص</h1>
                    <?php if(!empty($texts)) {?>
                        <div class="boxes">
                            <?php foreach($texts as $text):?> 
                                <a href="?text=<?php echo $text['id'] ?>" class="box">
                                    <h3>- <?php echo $text['category']?></h3>
                                    <div>
                                        <p>
                                            <?php echo $text['content']?>
                                        </p>
                                        <img src="images/documents.avif" alt="">
                                    </div>
                                </a>
                            <?php endforeach;?>
                        </div>
                    <?php } else { ?>
                        <div class="no-data">
                            <img src="images/Empty-amico.png" alt="">
                            <p>لا توجد نصوص لعرضها</p>
                        </div>
                    <?php }?>
                    <a href="add_text.php" class="add-text-btn">إضافة نص جديد <i class="fa-solid fa-pencil"></i></a>
                </div>
            </div>
    <?php }elseif($checker && intval($checker) && !isset($_GET['type'])  && !isset($_GET['participation'])) {
        $query = $con->prepare("SELECT * FROM texts WHERE id = ?"); 
        $query->execute(array(intval($checker)));
        if($query->rowCount() != 0) {
            $text = $query->fetchAll()[0];
            
            // Change status 
            if(isset($_POST['status_text'])) {
                $status = $con->prepare("UPDATE texts SET status = ? WHERE id = ?"); 
                if($text['status'] == 1 ) {
                    $status->execute(array(0, intval($checker)));
                    header("location: texts.php?text=$text[id]");
                    exit;
                }else {
                    $status->execute(array(1, intval($checker)));
                    header("location: texts.php?text=$text[id]");
                    exit;
                }
            }

             // Delete the text 
            if(isset($_POST['delete_text'])) {
                $status = $con->prepare("DELETE FROM texts WHERE id = ?"); 
                $status->execute(array($checker));
                $_SESSION['msgClass'] = "show-msg error";
                header("location: texts.php");
                exit;
            }
        ?>
        
            <div class="main-content">
                <p class="msg <?php echo $_SESSION['msgClass'] ?>">
                    تم التعديل بنجاح
                    <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
                </p>
                <?php $_SESSION['msgClass'] = '' ?>
                <div class="text-panel">
                    <h3 class="main-title">النص</h3>
                    <table>
                        <tr>
                            <th>تصنيف النص</th>
                            <th>محتوى النص</th>
                            <th>الحالة</th>
                            <th>خيارات</th>
                        </tr>
                        <tr>
                            <td><?php echo $text['category']?></td>
                            <td class="text-content"><?php echo $text['content']?></td>
                            <td><?php echo $text['status'] == 1 ? "معروض" : "مخفي"?></td>
                            <td class="text-tool">
                                <form method="post" action="" class="text-status">
                                    <button type="submit" name="status_text">
                                        <?php echo $text['status'] == 1 ? "إخفاء" : "إظهار" ?>
                                    </button>
                                </form>
                                <form method="post" action="" onsubmit="return confirmDelete()" class="text-status">
                                    <button type="submit" name="delete_text">حذف</button>
                                </form>
                            
                                <a href="?text=<?php echo $text['id']?>&type=edit" class="edit-text">تعديل</a>
                            </td>
                        </tr>
                    </table> 
                    <hr>
                    <?php 
                        

                        // Users table 
                        $sql = $con->prepare("SELECT users.*, participations.* FROM participations 
                            LEFT JOIN users 
                            ON participations.user_id = users.id WHERE participations.text_id = ?"); 
                        $sql->execute(array($checker));

                        if($sql->rowCount() != 0) {
                            $allData = $sql->fetchAll();  ?>
                            <table class="user-participations">
                                <tr>
                                    <th>#</th>
                                    <th>الإسم</th>
                                    <th>المنطقة</th>
                                    <th>تاريخ الميلاد (يوم\شهر\سنة)</th>
                                    <th>التسجيل الصوتي</th>
                                    <th>التقييم (10)</th>
                                    <th>الملاحظات</th>
                                    <th>خيارات</th>
                                </tr>
                                <?php foreach($allData as $data):?>
                                    <tr>
                                        <?php $date = date_create($data['birth_day'])?>
                                        <td><?php echo $data['id'] ?></td>
                                        <td><?php echo $data['name'] ?></td>
                                        <td><?php echo  $data['country'] ?></td>
                                        <td><?php echo date_format($date, 'Y/m/d') ?></td>
                                        <td><audio src="../<?php echo $data['record_path'] ?>" controls></audio></td>
                                        <td>
                                            <?php if(!empty($data['rating'])): ?>
                                                10 من <?php echo  $data['rating'] ?>
                                            <?php endif;?>
                                        </td>
                                        <td class="notes-col"><?php echo  $data['notes'] ?></td>
                                        <td>
                                            <a href="?text=<?php echo $text['id']?>&participation=<?php echo $data['id']?>" class="edit-text">إعدادات</a>
                                        </td>   
                                    </tr>
                                <?php endforeach;?>
                            </table>
                    <?php }else { ?>
                            
                           <h2 class="no-participations">لا توجد مشاركات لعرضها</h2>
                       <?php }?>
                </div>
 <?php  }  
    }elseif(isset($checker) && isset($_GET['participation'])) { 

       // Add rating and notes 
       $query = $con->prepare("SELECT * FROM participations WHERE id = ?"); 
       $query->execute(array($_GET['participation']));

       if($query->rowCount() != 0) {
           $textData = $query->fetchAll()[0];
       ?>
           <div class="main-content">
               <div class="notes-form">
                    <p class="msg <?php echo $_SESSION['msgClass'] ?>">
                        تم التحديث بنجاح
                        <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
                    </p>
                    <?php $_SESSION['msgClass'] = '' ?>
                   <form action="?text=<?php echo $checker ?>&participationid=<?php echo $textData['id']?>&type=praticipationUpdate" method="POST">
                       <h3> ملاحظات و تقييم</h3>
                       <div class="rating">
                           <label>التقييم</label>
                           <input type="number"  max="10" name="rating" placeholder="التقييم سيكون من 10، اكتب الرقم فقط" value="<?php echo $textData['rating'] ?? ''?>">
                       </div>
                       <div class="notes">
                            <label for="note1">أخطاء الإبدال</label>
                            <input type="checkbox" name="notes[]" id="note1" value="اخطاء الإبدال">
                            <label for="note2">أخطاء الإضافة</label>
                            <input type="checkbox" name="notes[]" value="أخطاء الإضافة" id="note2">
                            <label for="note3">أخطاء التكرار</label>
                            <input type="checkbox" name="notes[]" value="أخطاء التكرار" id="note3">
                            <label for="note4">أخطاء الحذف</label>
                            <input type="checkbox" name="notes[]" value="أخطاء الحذف" id="note4">
                            <label for="note5">أخطاء في أسلوب الإستفهام</label>
                            <input type="checkbox" name="notes[]" value="أخطاء في أسلوب الإستفهام" id="note5">
                            <label for="note6">المد الزائد لحرف غير ممدود</label>
                            <input type="checkbox" name="notes[]" value="المد الزائد لحرف غير ممدود" id="note6">
                            <label for="note7">الوقف والإبتداء القبيح</label>
                            <input type="checkbox" name="notes[]" value="الوقف والإبتداء القبيح" id="note7">
                            <label for="note8">أسلوب الإلقاء رتيب وغير متفاعل </label>
                            <input type="checkbox" name="notes[]" value="أسلوب الإلقاء رتيب وغير متفاعل" id="note8">
                            <label for="note9">أخطاء في الصرفي</label>
                            <input type="checkbox" name="notes[]" value="أخطاء في الصرفي" id="note9">
                            <label for="note10">أخطاء في الإعراب</label>
                            <input type="checkbox" name="notes[]" value="أخطاء في الإعراب" id="note10">
                            <label for="note11">أخطاء القراءة العكسية</label>
                            <input type="checkbox" name="notes[]" value="أخطاء القراءة العكسية" id="note11">
                       </div>
                          
                       <input type="submit" value="إضافة">
                   </form>
               </div>
           </div> 
    <?php }else {
            header("Location: texts.php");
            exit;
        }
     }elseif(isset($checker) && $_GET['type'] == 'praticipationUpdate' ) {
        // تحقق من إرسال النموذج
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // تحقق من وجود بيانات checkbox
            if(isset($_POST['notes']) && is_array($_POST['notes'])) {
                // تحول القيم إلى سلسلة نصية وقم بتخزينها في قاعدة البيانات
                $notes = implode("، ", $_POST['notes']);
                $rating = $_POST['rating'];
                $sql = $con->prepare("UPDATE participations SET notes = ?, rating = ? WHERE id = ? ");
                $sql->execute(array($notes, $rating, $_GET['participationid']));
                $_SESSION['msgClass'] = 'show-msg success';
                header("Location: texts.php?text=$checker");
            } else {
                echo "الرجاء تحديد ميزة واحدة على الأقل!";
            }
        }
     }elseif($checker && $_GET['type'] == 'edit') {

        // Get Data
        $query = $con->prepare("SELECT * FROM texts WHERE id = ?"); 
        $query->execute(array($checker));

        if($query->rowCount() != 0) {
            $textData = $query->fetchAll()[0];
        ?>
            <div class="main-content">
                <div class="add-form">
                    <form action="?text=<?php echo $textData['id']?>&type=update" method="POST">
                        <h3>تعديل النص </h3>
                        <div>
                            <label>التصنيف</label>
                            <input type="text" name="category" placeholder="اكتب تصنيف النص" required value="<?php echo $textData['category'] ?>">
                        </div>
                        <div>
                            <label for="status">الحالة</label>
                            <select name="status">
                                <option value="1" <?php echo $textData['status'] == 1 ? "selected" : '' ?>>إظهار</option>
                                <option value="0" <?php echo $textData['status'] == 0 ? "selected" : '' ?>>إخفاء</option>
                            </select>
                        </div>
                        <div>
                            <label>المحتوى</label>
                            <textarea name="content" placeholder="اكتب محتوى الرسالة......" required><?php echo $textData['content'] ?></textarea>
                        </div>
                        <input type="submit" value="إضافة">
                    </form>
                </div>
            </div> 
        
  <?php };
    }elseif ($checker && $_GET['type'] == 'update') {
       $content  = $_POST['content'];
       $category =  $_POST['category'];
       $status   =  $_POST['status'];

       $updateSql = $con->prepare("UPDATE texts SET content = ?, category = ?, status = ? WHERE id = ?");
       $updateSql->execute(array($content, $category, $status, $checker));
       $_SESSION['msgClass'] = 'show-msg success';
       header("location: ?text=$checker");
       exit;
    }
include 'footer.php'?>

