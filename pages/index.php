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
               <div class="d-flex justify-content-around flex-wrap">
                   <div class="content bg-dark">
                       number of employees <?php echo getCount('employee')?>
                   </div>
                   <div class="content bg-dark">
                       number of orders <?php echo getCount('_order')?>
                   </div>
                   <div class="content bg-dark">
                       number of schools <?php echo getCount('school')?>
                   </div>
               </div>

                <div class="background-image">
                    <img src="<?php echo ASSETS . 'images/Electronic-management.jpg'?>" alt="">
                </div>
            </div>
        </div>
    </div>
    <!--  End main    -->

<?php
    require_once BLP . 'shared/footer.php';
?>