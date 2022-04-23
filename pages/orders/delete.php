<?php
    require '../../config.php';
    require BLP . 'shared/header.php';

    if ($_SESSION['role'] === '1') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $orderId = $_GET['id'];
        $result = deleteRow("DELETE FROM `_order` WHERE `id`= $orderId");
        if ($result['boolean'] === true) {
            $success_message = $result['message'];
            header( "refresh:2;url=".BASEURLPAGES."orders/viewParentOrder.php");
        } else {
            $error_message = $result['message'];
        }

        require BL . 'utils/error.php';
    }

    require BLP . 'shared/footer.php';