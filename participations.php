
<?php 
    include 'connect.php';
    include 'header.php';

    $sql = $con->prepare("SELECT * FROM participations WHERE participations.user_id = ?"); 
    $sql->execute(array($_SESSION['userid']));

    if($sql->rowCount() != 0) { 
        $myData = $sql->fetchAll();
    ?>
    <div class="participations-section">
        <h3 class="main-title">مشاركاتي</h3>
        <?php foreach($myData as $data): ?>
                <table class="participations-table">
                    <tr>
                        <th>#</th>
                        <th>التقييم (10)</th>
                        <th>تسجيلاتي</th>
                    </tr>
                
                    
                        <tr>
                            <td><?php echo $data['id']?></td>
                            <td>
                                <?php if(isset($data['rating'])) {?>
                                    <?php echo $data['rating']?>/10
                                <?php }else {
                                    echo "ليس هنالك تقييم بعد.";
                                }?>
                            </td>
                            <td><audio src="<?php echo $data['record_path'] ?>" controls></audio></td>
                        </tr>
                </table>
        <div class="showNotes">
            <h3>الملاحظات</h3>
            <?php 
                if(isset($data['notes'])) {
                    $notes = explode('،' ,  $data['notes']); 

                    foreach($notes as $note):
                        echo "<p><i class='fa-solid fa-circle'></i> $note</p>";
                    endforeach;
                }else {
                    echo 'ليس هنالك ملاحظات بعد';
                }
            ?>

        </div>
        <?php endforeach;?>
    </div>

<?php }else { ?>
        <div class="no-data">
            <img src="images/Empty-amico.png" alt="">
            <p>لا توجد مشاركات لعرضها</p>
        </div>
    <?php }
    include 'footer.php'; ?>

