<?php
    require '../config.php';
    require BLP . 'shared/header.php';
?>

<!--  start main    -->
<div class="main" id="main">
    <div class="container-fluid">
        <div class="content- text-end">
            <p class="lead">
                من خلال هذه المنصه
            </p>
            <p>If you want to enter here <a href="<?php echo BASEURLPAGES . 'auth/login.php'?>">Login</a></p>
            <div class="content-img">
                <img src="<?php echo ASSETS . 'images/info.jpg';?>" alt="">
            </div>
        </div>
    </div>
    

</div>
<!--  End main    -->
<?php
    require BLP . 'shared/footer.php';
?>