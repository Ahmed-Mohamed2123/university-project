<?php
    require '../../config.php';

    session_destroy();
    header("location:".BASEURLPAGES.'auth/login.php');