<?php
    require '../../config.php';
    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';
?>


<?php
    if (isset($_POST['submit'])) {
        $email = sanitizeEmail($_POST['email']);
        $password = sanitizeString($_POST['password']);
        $username = sanitizeString($_POST['username']);
        $address = sanitizeString($_POST['address']);
        $national_id = sanitizeInteger($_POST['national_id']);
        $radio = $_POST['radio'];

        if (checkEmpty($email) && checkEmpty($password)) {
            if (validEmail($email)) {
                $emailExist = getRow('users', 'email', $email);
                if ($emailExist) {
                    $error_message = 'هذا الايميل مسجل به سابقا , من فضلك اكتب ايميل اخر.';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    if ($radio == 'parent') {
                        $res = setDataUser(
                            'parent',
                            $username,
                            $address,
                            $national_id,
                            $email,
                            $password,
                            0);

                        if ($res['boolean'] === true) {
                            $success_message = $res['message'];
                            header("Refresh:3; url=". BASEURLPAGES . 'auth/login.php');
                        } else {
                            $error_message = $res['message'];
                        }

                    } else if ($radio == 'employee') {
                        $res = setDataUser(
                            'employee',
                            $username,
                            $address,
                            $national_id,
                            $email,
                            $password,
                            1);

                        if ($res['boolean'] === true) {
                            $success_message = $res['message'];
                            header("Refresh:3; url=". BASEURLPAGES . 'auth/login.php');
                        } else {
                            $error_message = $res['message'];
                        }
                }



                }
            } else {
                $error_message = "من فضلك اكتب ايميل صحيح";
            }

        } else {
            $error_message = "مطلوب ملأ حقل الايميل والرقم السري";
        }
    }


    function setDataUser(
            $valueChecked,
            $username,
            $address,
            $national_id,
            $email,
            $password,
            $role
    ) {

        $username = !checkEmpty($username) ? 'NULL' : "'$username'";
        $address = !checkEmpty($address) ? 'NULL' : "'$address'";
        $national_id = !checkEmpty($national_id) ? 'NULL' : "'$national_id'";

        $sql = "INSERT INTO `$valueChecked` (`username`,`address`, `national_id`)
                            VALUES ( $username, $address,$national_id) ";
        $isAdded = db_insert($sql);

        if ($isAdded['boolean'] === true) {

            $parentId = $valueChecked == 'parent' ? $isAdded['id'] : 'NULL';
            $employeeId = $valueChecked == 'employee' ? $isAdded['id'] : 'NULL';

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (`email`,`password`, `role`, `parentId`, `employeeId`)
                            VALUES ('$email','$hashed_password', '$role', $parentId, $employeeId) ";
            return db_insert($sql);
        }
    }

require BL . 'utils/error.php';

?>

<!--  start main    -->
<div class="main auth" id="main">
    <div class="container-fluid">
        <div class="register p-4">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <h3 class="text-end">تسجيل</h3>
                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radio" value="parent" id="flexRadioDefault1" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            ولي الأمر
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radio" value="employee" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            موظف
                        </label>
                    </div>
                </div>

                <input class="form-control mb-2" type="text" name="email" placeholder="Enter your email">
                <input class="form-control mb-2" type="password" name="password" placeholder="Enter your password">
                <input class="form-control mb-2" type="text" name="username" placeholder="Enter your username">
                <input class="form-control mb-2" type="text" name="address" placeholder="Enter your address">
                <input class="form-control mb-2" type="number" name="national_id" placeholder="Enter your national_id">

                <button type="submit" name="submit" class="btn btn-danger w-100">تسجيل</button>
            </form>
        </div>
    </div>
</div>
<!--  End main    -->
<?php
    require '../shared/footer.php';
?>