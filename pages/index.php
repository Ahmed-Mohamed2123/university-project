<?php
    require_once '../config.php';
    require_once BLP . 'shared/header.php';

    if (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

?>

    <!--  start main    -->
    <div class="main index" id="main">
        <div class="container-fluid">
            <div class="dashboard-content" id="dashboard-content">
<!--               <div class="d-flex justify-content-around flex-wrap">-->
<!--                   <div class="content bg-dark">-->
<!--                       number of employees --><?php //echo getCount('employee')?>
<!--                   </div>-->
<!--                   <div class="content bg-dark">-->
<!--                       number of orders --><?php //echo getCount('_order')?>
<!--                   </div>-->
<!--                   <div class="content bg-dark">-->
<!--                       number of schools --><?php //echo getCount('school')?>
<!--                   </div>-->
<!--               </div>-->

                <?php if (intval($_SESSION['role']) === 0) { ?>
                    <div class="content-parent text-end fw-bold text-black">
                        يمكنك اجراء طلب استخراج نتيجه ابنك من خلال الضغط على <a href="<?php echo BASEURLPAGES . 'orders/add.php';?>">انشاء طلب</a>
                    </div>
                <?php }?>

                <?php if (intval($_SESSION['role']) === 1) { ?>
                    <div class="content-parent text-end fw-bold text-black">
                        مرحبا بك , انت جزأ من فريق العمل معنا , ونحن نقدر جهوداتك ,, يمكنك الدخول الى صفحه الطلبات من هنا <a href="<?php echo BASEURLPAGES . 'orders/viewAll.php';?>">الطلبات</a>
                    </div>
                <?php }?>

                <div class="background-image">
                    <?php if (intval($_SESSION['role']) === 0) { ?>
                        <img src="<?php echo ASSETS . 'images/Electronic-management.jpg'?>" alt="">
                    <?php }?>

                    <?php if (intval($_SESSION['role']) === 1) { ?>
                        <img src="<?php echo ASSETS . 'images/employee.png'?>" alt="">
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <!--  End main    -->

<?php
    require_once BLP . 'shared/footer.php';
?>