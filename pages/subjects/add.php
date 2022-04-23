<?php
    require '../../config.php';
    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';
    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    if (isset($_POST['submit'])) {
        $name = sanitizeString($_POST['name']);

        if (checkEmpty($name)) {
            $sql = "INSERT INTO subject (`subject_name`) VALUES ('$name')";
            $result = db_insert($sql);

            if ($result['boolean'] === true) {
                $success_message = $result['message'];
            } else {
                $error_message = $result['message'];
            }

        } else {
            $error_message = "Please Fill name Field";
        }

        require BL . 'utils/error.php';
    }

?>
    <!--  start main    -->
    <div class="main subject" id="main">
        <div class="pagesForm p-3">
            <h3 class="mb-0 text-white">Add new Subject</h3>
            <hr class="bg-white">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="mb-2">
                    <input
                            class="form-control"
                            type="text"
                            name="name"
                            placeholder="enter your subject name" >
                </div>

                <button
                        class="btn btn-danger"
                        type="submit"
                        name="submit">save</button>
            </form>
        </div>
    </div>
    <!--  End main    -->
<?php
require BLP . 'shared/footer.php';
?>