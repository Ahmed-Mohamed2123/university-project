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
        $subjectId = $_GET['id'];
        $sql = "SELECT * FROM subject WHERE id = '$subjectId'";
        $getDataById = getRow($sql);
        if (!$getDataById) {
            header("location:".BASEURLPAGES . 'subjects/viewAll.php');
        }
        $subject_id = $getDataById['id'];


    } else {
        header("location:" . BASEURL . 'index.php');
    }
?>
    <!--  start main    -->
    <div class="main subject" id="main">
        <div class="pagesForm p-3">
            <h3 class="mb-0 text-white">Edit subject</h3>
            <hr class="bg-white">
            <form method="post" action="<?php echo BASEURLPAGES . 'subjects/update.php'; ?>">
                <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>" class="form-control" >
                <div class="mb-2">
                    <input
                        class="form-control"
                        type="text"
                        name="name"
                        value="<?php echo $getDataById['subject_name']; ?>"
                        placeholder="enter your school name" >
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