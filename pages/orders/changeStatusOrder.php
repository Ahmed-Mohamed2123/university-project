<?php

    require '../../config.php';
    require BLP . 'shared/header.php';

    if(
        isset($_GET['id']) && is_numeric($_GET['id']) &&
        isset($_GET['request']) && is_string($_GET['request'])) {
        $typeRequest = $_GET['request'];
        $orderId = $_GET['id'];

        if ($typeRequest === 'reject') {
            $result_reject = db_update("UPDATE `_order` SET `order_status`= 2 WHERE `id`=$orderId");
            if ($result_reject['boolean'] === true) {
                $success_message = $result_reject['message'];
                header( "refresh:2;url=".BASEURLPAGES."orders/viewAll.php");
            } else {
                $error_message = $result_reject['message'];
            }
        } elseif ($typeRequest === 'accept') {
            $result_accept = db_update("UPDATE `_order` SET `order_status`= 1 WHERE `id`=$orderId");
            if ($result_accept['boolean'] === true) {
                $success_message = $result_accept['message'];
                header( "refresh:2;url=".BASEURLPAGES."orders/viewAll.php");
            } else {
                $error_message = $result_accept['message'];
            }
        }

        require BL . 'utils/error.php';
    }

    require BLP . 'shared/footer.php';