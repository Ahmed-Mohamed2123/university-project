<?php
    require '../../config.php';
    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';

    if ($_SESSION['role'] === NULL) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    if (isset($_POST['submit'])) {
        $profile_id = $_POST['profile_id'];
        $typeUser = $_POST['typeUser'];
        $username = sanitizeString($_POST['username']);
        $address = sanitizeString($_POST['address']);
        $national_id = sanitizeInteger($_POST['national_id']);


        $sql = "UPDATE $typeUser SET `username`='$username', `address` = '$address', `national_id` = $national_id WHERE `id`= $profile_id";
        $result = db_update($sql);

        if ($result['boolean'] === true) {
            $_SESSION['username'] = $username;
            $success_message = $result['message'];
            header( "refresh:2;url=".BASEURLPAGES."index.php");
        } else {
            $error_message = $result['message'];
        }



        require BL.'utils/error.php';

    }

    require BLP . 'shared/footer.php';