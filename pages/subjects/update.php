<?php
    require '../../config.php';

    require BLP.'shared/header.php';
    require BL.'utils/validate.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    if (isset($_POST['submit'])) {
        $subject_id = $_POST['subject_id'];

        $name = sanitizeString($_POST['name']);

        $sql = "UPDATE subject SET `subject_name`='$name' WHERE `id`= $subject_id ";
        $result = db_update($sql);

        if ($result['boolean'] === true) {
            $success_message = $result['message'];
            header( "refresh:2;url=".BASEURLPAGES."subjects/viewParentOrder.php");
        } else {
            $error_message = $result['message'];
        }



        require BL.'utils/error.php';
    }

    require BLP.'shared/footer.php';

