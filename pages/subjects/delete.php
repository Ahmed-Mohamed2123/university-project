<?php
    require '../../config.php';

    require BLP.'shared/header.php';
    require BL.'utils/validate.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $subject_id = $_GET['id'];

        $sql = "DELETE FROM `subject` WHERE `id`= $subject_id ";
        $result = deleteRow($sql);

        if ($result['boolean'] === true) {
            $success_message = $result['message'];

            header( "refresh:2;url=".BASEURLPAGES."subjects/viewAll.php");
        } else {
            $error_message = $result['message'];
        }



        require BL.'utils/error.php';
    }

    require BLP.'shared/footer.php';
