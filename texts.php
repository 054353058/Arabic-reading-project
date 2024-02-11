<?php 
    include 'connect.php';
    include 'header.php';
    
    $checker = isset($_GET['text']) && $_GET['text'] != '' ? $_GET['text'] : '';

    if($checker == '' || $checker == 'all') {

        $query = $con->prepare("SELECT * FROM texts WHERE status = 1"); 
        $query->execute(array());
        $texts = $query->fetchAll();
        ?>
            <div class="texts">
                <div class="main-content">
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
                </div>
            </div>
    <?php }elseif($checker && intval($checker) && !isset($_GET['type'])) {
        $query = $con->prepare("SELECT * FROM texts WHERE id = ?"); 
        $query->execute(array($checker));

        if($query->rowCount() != 0) {
            $text = $query->fetchAll()[0]?>
    
            <div class="texts">
                <div class="main-content">
                <p class="msg">
                    
                </p>
                    <h1># <?php echo $text['id']?></h1>
                    <div class="boxes show-text">
                        <div class="box">
                            <h3>التصنيف:  <?php echo $text['category']?></h3>
                            <div>
                                <p>
                                    <?php echo $text['content']?>
                                </p>
                                <img src="images/documents.avif" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="recording-sec">
                        <button id="mic" class="mic-toggle">
                            <i class="fa-solid fa-microphone"></i>
                        </button>
                        <audio class="playback" controls></audio>
                        <form onsubmit="saveAudio(event)" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                            <p onclick="deleteRecord()" class="delete-record">حذف</p>
                            <input type="submit" value="إرسال">
                            <div>
                                <input type="checkbox" name="record_permession" id="checkBox_permission">
                                <label for="checkBox_permission">
                                    أسمح باستخدام مشاركتي في البحث اللغوي والمعالجة للغة في البحث العلمي والتطبيقات اللغوية 
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
    <?php  }else {
            header("location: texts.php");
            exit;
        }
    }

include 'footer.php'?>