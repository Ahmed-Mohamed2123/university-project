<?php
    require '../../config.php';
    require BLP . 'shared/header.php';

    $id = intval($_SESSION['id']);
    $typeUser = NULL;
    if ($_SESSION['role'] === '0') {
        $typeUser = 'parent';
    } else {
        $typeUser = 'employee';
    }
    $profileData = getRow("SELECT * FROM $typeUser WHERE `id` = $id");

    if (!$profileData) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }
?>

<div class="main profile" id="main">
    <div class="pagesForm p-4">
        <h3 class="mb-0 text-white text-end">تعديل الملف الشخصى</h3>
        <hr class="bg-white">
        <form method="post" action="<?php echo BASEURLPAGES . 'profile/update.php'; ?>">
            <input type="hidden" name="profile_id" value="<?php echo $id; ?>" >
            <input type="hidden" name="typeUser" value="<?php echo $typeUser; ?>" >
            <div class="mb-2">
                <input
                    class="form-control"
                    type="text"
                    name="username"
                    value="<?php echo $profileData['username']; ?>"
                    placeholder="enter your school name" >
            </div>

            <div class="mb-2">
                <input
                    class="form-control"
                    name="address"
                    type="text"
                    value="<?php echo $profileData['address']; ?>"
                    placeholder="enter your school address" >
            </div>

            <div class="mb-2">
                <input
                    class="form-control"
                    name="national_id"
                    type="number"
                    value="<?php echo $profileData['national_id']; ?>"
                    placeholder="enter your school address" >
            </div>

            <button
                class="btn btn-danger w-100"
                type="submit"
                name="submit">احفظ</button>
        </form>
    </div>
</div>

<?php require BLP . 'shared/footer.php'; ?>
