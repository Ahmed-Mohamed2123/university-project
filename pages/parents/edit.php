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
        $parentId = $_GET['id'];
        $sql = "SELECT * FROM parent WHERE id = '$parentId'";
        $getDataById = getRow($sql);
        if (!$getDataById) {
            header("location:".BASEURLPAGES . 'parents/viewAll.php');
        }

        $school_id = $getDataById['id'];

    } else {
        header("location:" . BASEURL . 'index.php');
    }
?>

<!--  start main    -->
<div class="main parent" id="main">
    <div class="pagesForm p-3">
        <h3 class="mb-0 text-white">Edit parent information</h3>
        <hr class="bg-white">
        <form method="post" action="<?php echo BASEURLPAGES . 'parents/update.php'; ?>">
            <input type="hidden" name="parent_id" value="<?php echo $parentId; ?>" class="form-control" >
            <div class="mb-2">
                <input
                    class="form-control"
                    type="text"
                    name="username"
                    value="<?php echo $getDataById['username']; ?>"
                    placeholder="enter your username" >
            </div>

            <div class="mb-2">
                <input
                    class="form-control"
                    name="address"
                    value="<?php echo $getDataById['address']; ?>"
                    placeholder="enter your address" >
            </div>

            <div class="mb-2">
                <input
                    class="form-control"
                    name="national_id"
                    value="<?php echo $getDataById['national_id']; ?>"
                    placeholder="enter your national_id" >
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
