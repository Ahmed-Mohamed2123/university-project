<?php require '../../config.php';

require BLP.'shared/header.php';
require BL.'utils/validate.php';

if ($_SESSION['role'] === '0') {
    header('location:' . BASEURLPAGES . 'index.php');
} elseif (!isset($_SESSION['role'])) {
    header('location:' . BASEURLPAGES . 'auth/login.php');
}

if(isset($_POST['submit']))
{
    $school_id = $_POST['school_id'];

    $name = sanitizeString($_POST['school_name']);
    $address = sanitizeString($_POST['school_address']);

    $sql = "UPDATE school SET `school_name`='$name', `school_address` = '$address' WHERE `id`= $school_id ";
    $result = db_update($sql);
    if ($result['boolean'] === true) {
        $success_message = $result['message'];
        header( "refresh:2;url=".BASEURLPAGES."schools/viewParentOrder.php");
    } else {
        $error_message = $result['message'];
    }



    require BL.'utils/error.php';
}




require BLP.'shared/footer.php';

