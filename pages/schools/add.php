<?php
    require '../../config.php';
    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }
?>

<?php

    if (isset($_POST['submit'])) {
        $name = sanitizeString($_POST['name']);
        $address = sanitizeString($_POST['address']);
        if (checkEmpty($name) && checkEmpty($address)) {
            $sql = "INSERT INTO school (`school_name`, `school_address`) VALUES ('$name', '$address')";
            $result = db_insert($sql);

            if ($result['boolean'] === true) {
                $success_message = $result['message'];
            } else {
                $error_message = $result['message'];
            }

        } else {
            $error_message = "من فضلك املأ جميع الحقول";
        }

        require BL . 'utils/error.php';
    }

?>
<!--  start main    -->
<div class="main school" id="main">
    <div class="pagesForm p-3">
        <h3 class="mb-0 text-white text-end">اضافه مدرسه</h3>
        <hr class="bg-white">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-2">
                <input
                        class="form-control"
                        type="text"
                        name="name"
                        placeholder="enter your school name" >
            </div>

            <div class="mb-2">
                <input
                        class="form-control"
                        name="address"
                        placeholder="enter your school address" >
            </div>

            <button
                    class="btn btn-danger w-100"
                    type="submit"
                    name="submit">حفظ</button>
        </form>
    </div>
</div>
<!--  End main    -->
<?php
    require BLP . 'shared/footer.php';
?>