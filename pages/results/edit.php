<?php
    require '../../config.php';
    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    $subject_data = [];
    $student_name = NULL;
    $modified_degree = [];
    $sitting_number = NULL;
    $schoolId = NULL;

    if (isset($_GET['sitting_number']) && is_numeric($_GET['sitting_number']) &&
        isset($_GET['schoolId']) && is_numeric($_GET['schoolId'])) {
        $sitting_number = $_GET['sitting_number'];
        $schoolId = $_GET['schoolId'];
        $getStudent = getRow("SELECT DISTINCT(`student_name`) FROM `result` WHERE result.sitting_number = $sitting_number AND `schoolId` = $schoolId");
        $student_name = $getStudent['student_name'];
        $resultSql = "SELECT `subject_name`, `degree`, `max_degree`, `min_degree`, result.id FROM `result` LEFT JOIN subject ON result.subjectId = subject.id WHERE `student_name` = '$student_name' AND `schoolId` = $schoolId";
        $subject_data[] = getResults($resultSql);

        if ($getStudent['boolean'] === false && count($subject_data) <= 0) {
            header('location:' . BASEURLPAGES . 'results/viewAll.php');
        }

    } else {
        header('location:' . BASEURLPAGES . 'index.php');
    }
?>

    <!--  start main    -->
    <div class="main" id="main">
        <div class="p-3">
            <form  method="post" action="<?php echo BASEURLPAGES . 'results/update.php'; ?>">
                <input type="hidden" name="sitting_number" value="<?php echo $sitting_number; ?>" class="form-control" >
                <input type="hidden" name="schoolId" value="<?php echo $schoolId; ?>" class="form-control" >
                <div class="row">
                    <div class="col-md-6">
                        <input
                            class="form-control"
                            name="student_name"
                            value="<?php echo $student_name; ?>"
                            type="text"
                            placeholder="student_name">
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <?php foreach ($subject_data[0] as $row) {  ?>
                                <th scope="col">
                                    <?php echo $row['subject_name'];?>
                                </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php for ($t = 0; $t < count($subject_data[0]); $t++) {  ?>
                                <td>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="<?php echo $subject_data[0][$t]['subject_name'];?>"
                                        value="<?php echo $subject_data[0][$t]['degree'];?>">
                                </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>

                <div class="w-100 d-flex justify-content-end">
                    <button type="submit" name="submit" class="btn btn-danger">??????</button>
                </div>
            </form>
        </div>
    </div>
<?php
    require BLP . 'shared/footer.php';
?>