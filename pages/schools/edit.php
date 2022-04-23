<?php
    require '../../config.php';
    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';
    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $schoolId = $_GET['id'];
        $sql = "SELECT * FROM school WHERE id = '$schoolId'";
        $getSchoolById = getRow($sql);

        $school_id = $getSchoolById['id'];
        if (!$getSchoolById) {
            header("location:".BASEURLPAGES . 'schools/viewParentOrder.php');
        }


    } else {
        header("location:" . BASEURL . 'index.php');
    }
?>
    <!--  start main    -->
    <div class="main school" id="main">
        <div class="pagesForm p-3">
            <h3 class="mb-0 text-white">Edit School</h3>
            <hr class="bg-white">
            <form method="post" action="<?php echo BASEURLPAGES . 'schools/update.php'; ?>">
                <input type="hidden" name="school_id" value="<?php echo $school_id; ?>" class="form-control" >
                <div class="mb-2">
                    <input
                        class="form-control"
                        type="text"
                        name="school_name"
                        value="<?php echo $getSchoolById['school_name']; ?>"
                        placeholder="enter your school name" >
                </div>

                <div class="mb-2">
                    <input
                        class="form-control"
                        name="school_address"
                        value="<?php echo $getSchoolById['school_address']; ?>"
                        placeholder="enter your school address" >
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