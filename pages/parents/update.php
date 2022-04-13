<?php require '../../config.php';  ?>

<?php require BLP.'shared/header.php';  ?>
<?php require BL.'utils/validate.php';  ?>



<?php

if(isset($_POST['submit']))
{
    $parent_id = $_POST['parent_id'];

    $username = sanitizeString($_POST['username']);
    $address = sanitizeString($_POST['address']);
    $national_id = sanitizeInteger($_POST['national_id']);
    $sql = "UPDATE parent SET `username`='$username', `address` = '$address', `national_id` = $national_id WHERE `id`=$parent_id";
    $result = db_update($sql);

    if ($result['boolean'] === true) {
        $success_message = $result['message'];
        header( "refresh:2;url=".BASEURLPAGES."parents/viewAll.php");
    } else {
        $error_message = $result['message'];
    }



    require BL.'utils/error.php';
}


?>

<?php require BLP.'shared/footer.php';  ?>

