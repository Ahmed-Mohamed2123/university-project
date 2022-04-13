<?php
    require '../../config.php';
    require BLP.'shared/header.php';
    require BL.'utils/validate.php';
?>

<?php

if(isset($_GET['id']) && is_numeric($_GET['id']))
{
    $school_id = $_GET['id'];

    $sql = "DELETE FROM school WHERE `id`= $school_id ";
    $result = deleteRow($sql);

    if ($result['boolean'] === true) {
        $success_message = $result['message'];

        header( "refresh:3;url=".BASEURLPAGES."schools/viewAll.php");
    } else {
        $error_message = $result['message'];
    }



    require BL.'utils/error.php';
}


?>

<?php require BLP.'shared/footer.php';  ?>

