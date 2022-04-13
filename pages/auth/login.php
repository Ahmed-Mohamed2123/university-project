<?php
    require '../../config.php';
    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';

    if (isset($_SESSION['role'])) {
        header("location:".BASEURL);
    }
?>

<?php
if (isset($_POST['submit']))
{
    $password = sanitizeString($_POST['password']);
    $email = sanitizeEmail($_POST['email']);

    if (checkEmpty($email) && checkEmpty($password))
    {

        if (validEmail($email))
        {

            $sql = "SELECT * FROM users WHERE email='$email'";
            $check = getRow($sql);
            if ($check) {

                $check_password = password_verify($password, $check['password']);

                $parentId = $check['parentId'];
                $employeeId = $check['employeeId'];
                $profileData = [];

                if($check_password) {
                    if ($parentId) {
                        $sql = "SELECT * FROM users LEFT JOIN parent on users.parentId = parent.id WHERE email='$email'";
                        $profileData = getRow($sql);

                    } else if ($employeeId) {
                        $sql = "SELECT * FROM users LEFT JOIN employee on users.employeeId = employee.id WHERE email='$email'";
                        $profileData = getRow($sql);
                    }
                    $_SESSION['email'] = $check['email'];
                    $_SESSION['role'] = $check['role'];
                    $_SESSION['username'] = $profileData['username'];
                    $_SESSION['id'] = $profileData['id'];


                    header("location:".BASEURLPAGES);
                }
                else {
                    $error_message = "password is incorrect";
                }
            } else {
                $error_message = "email '$email' does not found in db";
            }
        }
        else
        {
            $error_message = "Type Correct Email";
        }
    }
    else
    {
        $error_message = "Please Fill All Fields";
    }
    require BL . 'utils/error.php';
}
?>


<!--  start main    -->
<div class="main auth" id="main">
    <div class="container-fluid">
        <div class="login p-4">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <h3>Login</h3>
                <hr>
                <input class="form-control mb-2" type="text" name="email" placeholder="Enter your email">
                <input class="form-control mb-2" type="password" name="password" placeholder="Enter your password">

                <button type="submit" name="submit" class="btn btn-danger">Login</button>
            </form>
        </div>
    </div>
</div>
<!--  End main    -->

<?php
require '../shared/footer.php';
?>