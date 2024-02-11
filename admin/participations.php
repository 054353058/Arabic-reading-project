<?php 
    include '../connect.php';
    include 'header.php';

    $sql = $con->prepare("SELECT users.*, COUNT(participations.id) AS participation_count 
                     FROM users 
                     LEFT JOIN participations ON users.id = participations.user_id 
                     GROUP BY users.id");
    $sql->execute();
    if($sql->rowCount() != 0) {
        $allData = $sql->fetchAll();


?>

<div class="participations">
    <div class="main-content">
        <h1 class="main-title">كل المسجلين</h1>
        <table>
            <tr>
                <th>الإسم</th>
                <th>تاريخ الميلاد (يوم\شهر\سنة)</th>
                <th>المنطقة</th>
                <th>عدد المشاركات</th>
            </tr>
            <?php foreach($allData as $data):?>
                <tr>
                    <?php $date = date_create($data['birth_day'])?>
                    <td><?php echo $data['name'] ?></td>
                    <td><?php echo date_format($date, 'Y/m/d') ?></td>
                    <td><?php echo  $data['country'] ?></td>
                    <td><?php echo $data['participation_count'] ?></td>
                </tr>
            <?php endforeach;?>
        </table>
        
    </div>
</div>
<?php } else { ?>
    <div class="no-data">
        <img src="images/Empty-amico.png" alt="">
        <p>لا توجد مشاركات لعرضها</p>
    </div>
<?php } 
include 'footer.php'?>