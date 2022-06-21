<?php
    require './../../config.php';

    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    function sqlQueries($limit, $student_name, $sitting_number, $grade, $semester, $school_id, $school_year) {
        $conditionCount = "
            WHERE
            `student_name` LIKE '%$student_name%' AND
            `sitting_number` LIKE '%$sitting_number%' AND
            `semester` LIKE '%$semester%' AND
            `grade` LIKE '%$grade%' AND
            `school_year` LIKE '%$school_year%' AND
            `schoolId` LIKE '%$school_id%'
            ";
        $conditionOrRestSql = "
                LEFT JOIN school ON result.schoolId = school.id
                LEFT JOIN subject ON result.subjectId = subject.id
                WHERE
                `student_name` LIKE '%$student_name%' AND
                `sitting_number` LIKE '%$sitting_number%' AND
                `semester` LIKE '%$semester%' AND
                `grade` LIKE '%$grade%' AND
                `school_year` LIKE '%$school_year%' AND
                `schoolId` LIKE '%$school_id%'
            ";

        return paginationResult('result', $limit, $conditionCount, $conditionOrRestSql);
    }


    unset(
        $_SESSION["student_name"],
        $_SESSION["sitting_number"],
        $_SESSION["semester"],
        $_SESSION["grade"],
        $_SESSION["school_year"],
        $_SESSION["school_id"],
    );

    $data_pagination = NULL;
    $subject_data = [];
    $total = [];
    $total_max_degree = [];

    if (isset($_GET['submit'])) {
        // search
        $student_name = $_SESSION['student_name'] = sanitizeString($_GET['student_name']);
        $sitting_number = $_SESSION['sitting_number'] = sanitizeInteger($_GET['sitting_number']);
        $semester = $_SESSION['semester'] = sanitizeString($_GET['semester']);
        $grade = $_SESSION['grade'] = sanitizeString($_GET['grade']);
        $school_id = $_SESSION['school_id'] = $_GET['school_id'];
        $school_year = $_SESSION['school_year'] = $_GET['school_year'];

        $x=0;
        $limit = 5;

        $data_pagination = sqlQueries($limit, $student_name, $sitting_number, $grade, $semester, $school_id, $school_year);

        for ($i = 0; $i <= count($data_pagination['data']); $i++) {
            if (!empty($data_pagination['data'][$i])) {
                $students_names = $data_pagination['data'][$i]['student_name'];
                $resultSql = "SELECT `subject_name`, `degree`, `max_degree`, `min_degree`, `schoolId` FROM `result` LEFT JOIN subject ON result.subjectId = subject.id WHERE `student_name` = '$students_names' AND `schoolId` = $school_id";
                $subject_data[] = getResults($resultSql);
            }
        }
    }


?>

<!--  start main    -->
<div class="main" id="main">
    <div class="resultsViewAll">

        <div class="search">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
                <input type="hidden" name="page" value="1">

                <div class="row">
                    <div class="col-md-6">
                        <select name="school_id" class="form-select mb-2" aria-label="Default select example">
                            <?php foreach (getRows('school') as $school) { ?>
                                <option
                                        value="<?php echo $school['id']; ?>"
                                        <?php if (($school['id'] === $_SESSION['school_id']) || empty($_SESSION['school_id'])) {echo 'selected';} ?>
                                        > <?php echo $school['school_name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="semester" class="form-select mb-2" aria-label="Default select example">
                            <option value="الفصل الدراسي الاول"  <?php if (('الفصل الدراسي الاول' === $_SESSION['semester']) || empty($_SESSION['semester'])) { echo 'selected'; } ?>>الفصل الدراسي الاول</option>
                            <option value="الفصل الدراسي الثانى"  <?php if ('الفصل الدراسي الثانى' === $_SESSION['semester']) { echo 'selected'; } ?>>الفصل الدراسي الثانى</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <input value="<?php echo $_SESSION['student_name'];?>" type="text" name="student_name" class="form-control mb-2" placeholder="search by name">
                    </div>
                    <div class="col-md-6">
                        <input value="<?php echo $_SESSION['sitting_number'];?>" type="number" name="sitting_number" class="form-control mb-2" placeholder="search by sitting_number">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input value="<?php echo $_SESSION['grade'];?>" type="text" name="grade" class="form-control mb-2" placeholder="search by grade">
                    </div>
                    <div class="col-md-6">
                        <input value="<?php echo $_SESSION['school_year'];?>" type="text" name="school_year" class="form-control mb-2" placeholder="search by school_year">
                    </div>
                </div>


                <div class="d-grid gap-2">
                    <button
                            class="btn btn-success"
                            name="submit"
                            type="submit">ابحث</button>
                </div>
            </form>
            <br>
        </div>

        <?php if ($data_pagination !== NULL) { ?>
            <div style="overflow-y: auto">
                <table class="table table-bordered text-center table-responsive" style="vertical-align: middle;">
                    <thead>
                    <tr style="vertical-align: middle;">
                        <th scope="col">#</th>
                        <th scope="col">اسم الطالب</th>
                        <th scope="col">السنه الدراسيه</th>
                        <th scope="col">رقم الجلوس</th>
                        <th scope="col"></th>
                        <?php foreach ($subject_data[0] as $row) {  ?>
                            <th scope="col">
                                <?php echo $row['subject_name'];?>
                            </th>
                        <?php } ?>
                        <th scope="col">مجموع الدرجات</th>
                        <th scope="col">النسبه المئويه</th>
                        <th scope="col">options</th>
                    </tr>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col">الدرجه العظمى</th>
                        <?php foreach ($subject_data[0] as $row) {  ?>
                            <th scope="col">
                                <?php echo $row['max_degree']; ?>
                            </th>
                        <?php } ?>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col">الدرجه الصغرى</th>
                        <?php foreach ($subject_data[0] as $row) {  ?>
                            <th scope="col">
                                <?php $total_max_degree[] = $row['min_degree']; echo $row['min_degree'];?>
                            </th>
                        <?php } ?>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data_pagination['data'] as $row) {  ?>
                        <tr>
                            <th scope="row"><?php echo $x; ?></th>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['school_year']; ?></td>
                            <td><?php echo $row['sitting_number']; ?></td>
                            <td></td>
                            <?php for ($t = 0; $t < count($subject_data[$x]); $t++) {  ?>
                                <td><?php   $total[$t] = $subject_data[$x][$t]['degree']; echo $subject_data[$x][$t]['degree'];?></td>
                            <?php } ?>
                            <td><?php echo array_sum($total); ?></td>
                            <td><?php echo floor((array_sum($total_max_degree) / array_sum($total)) * 100) . '%'; ?></td>

                            <td>
                                <a class="btn btn-primary" href="<?php echo BASEURLPAGES . 'results/edit.php?sitting_number=' . $row['sitting_number'] . '&schoolId=' . $_GET['school_id'];?>">تعديل</a>
                                <a class="btn btn-danger" href="<?php echo BASEURLPAGES . 'results/delete.php?sitting_number=' . $row['sitting_number'] . '&schoolId=' . $_GET['school_id'];?>">حذف</a>
                            </td>
                        </tr>
                        <?php $x++; } ?>
                    </tbody>
                </table>
            </div>

            <nav class="d-flex justify-content-center" aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a
                        <?php ($data_pagination['currentPage'] == $data_pagination['firstPage'] ? print 'disabled="disabled"' : '')?> class="page-link" href="?page=<?php echo $data_pagination['firstPage'] ?>&school_id=<?php echo $_GET['school_id'];?>&grade=<?php echo $_GET['grade'];?>&semester=<?php echo $_GET['semester']; ?>&student_name=<?php echo $_GET['student_name']; ?>&sitting_number=<?php echo $_GET['sitting_number'];?>&school_year=<?php echo $_GET['school_year'];?>&submit=" tabindex="-1" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Links of the pages with page number -->
                <?php for($i = $data_pagination['start']; $i <= $data_pagination['end']; $i++) { ?>
                    <li class='page-item <?php ($i == $data_pagination['currentPage'] ? print 'active' : '')?>'>
                        <a class='page-link'
                           href='?page=<?php echo $i?>&school_id=<?php echo $_GET['school_id'];?>&grade=<?php echo $_GET['grade'];?>&semester=<?php echo $_GET['semester']; ?>&student_name=<?php echo $_GET['student_name']; ?>&sitting_number=<?php echo $_GET['sitting_number'];?>&school_year=<?php echo $_GET['school_year'];?>&submit='
                        ><?php echo $i;?></a>
                    </li>
                <?php } ?>

                <li class="page-item">
                    <a <?php ($data_pagination['currentPage'] >= $data_pagination['total_pages'] ? print 'disabled="disabled"' : '')?> class="page-link" href="?page=<?php echo $data_pagination['lastPage'] ?>&school_id=<?php echo $_GET['school_id'];?>&grade=<?php echo $_GET['grade'];?>&semester=<?php echo $_GET['semester']; ?>&student_name=<?php echo $_GET['student_name']; ?>&sitting_number=<?php echo $_GET['sitting_number'];?>&school_year=<?php echo $_GET['school_year'];?>&submit=" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php } ?>
    </div>
</div>
<!--  End main    -->


<?php
    require BLP . 'shared/footer.php';
?>