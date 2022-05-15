<?php
    require '../../config.php';
    require BL . 'utils/validate.php';
    require BLP . 'shared/header.php';

    unset(
        $_SESSION["student_name_order"],
        $_SESSION["sitting_number_order"],
        $_SESSION["semester_order"],
        $_SESSION["grade_order"],
        $_SESSION["school_id_order"],
    );

    $data_pagination = NULL;
    $x=0;
    $limit = 5;
    $resultPrice = 30;

    if (isset($_GET['submit'])) {
        $student_name = $_SESSION['student_name_order'] = sanitizeString($_GET['student_name']);
        $sitting_number = $_SESSION['sitting_number_order'] = sanitizeInteger($_GET['sitting_number']);
        $semester = $_SESSION['semester_order'] = sanitizeString($_GET['semester']);
        $grade = $_SESSION['grade_order'] = sanitizeString($_GET['grade']);
        $school_id = $_SESSION['school_id_order'] = $_GET['school_id'];

        $conditionCount = "
            WHERE
            `student_name` LIKE '%$student_name%' AND
            `sitting_number` LIKE '%$sitting_number%' AND
            `semester` LIKE '%$semester%' AND
            `grade` LIKE '%$grade%' AND
            `schoolId` LIKE '%$school_id%'
            ";

        $conditionOrRestSql = "
                WHERE
                `student_name` LIKE '%$student_name%' AND
                `sitting_number` LIKE '%$sitting_number%' AND
                `semester` LIKE '%$semester%' AND
                `grade` LIKE '%$grade%' AND
                `schoolId` LIKE '%$school_id%'
            ";


        $data_pagination = paginationOrder('result', $limit, $conditionCount, $conditionOrRestSql);
    }

    if (isset($_POST['order_submit'])) {
        $id = $_POST['id'];
        for ($i = 0; $i <= count($data_pagination['data']); $i++) {
            if ($i == $id) {
                $_student_name = $data_pagination['data'][$i]['student_name'];
                $parentId = $_SESSION['id'];
                $dataRow = getRow("SELECT MIN(`id`) FROM result WHERE `student_name` = '$_student_name'");
                foreach ($dataRow as $resultId) {
                    $result = db_insert("INSERT INTO `_order`
                        (`order_status`, `parentId`, `resultId`, `price`) VALUES
                        (0, $parentId, $resultId, $resultPrice)");

                    if ($result['boolean'] === true) {
                        $success_message = $result['message'];
                        header("Refresh:3;url=" . BASEURLPAGES . 'orders/viewAll.php');
                    } else {
                        $error_message = $result['message'];
                    }
                }
            }
        }
    }

    require BL . 'utils/error.php';
?>

<!--  start main    -->
<div class="main" id="main">
    <div class="addNewOrder">

        <div class="search d-flex justify-content-between align-items-center">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#searchStudentModal">custom search</button>
            <p class="mb-0 fw-bold">Extracting the student's result <?php echo $resultPrice;?> egyptian pounds</p>
        </div>

        <?php if ($data_pagination['data'] !== NULL) { ?>
            <table class="table table-hover" style="cursor: pointer">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">student_name</th>
                        <th scope="col">options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_pagination['data'] as $row) {  ?>
                        <?php
                        $student_name_value = $row['student_name'];
                        $isExist = getRow("
                                    SELECT `student_name` FROM _order  
                                    LEFT JOIN result ON result.id = _order.resultId
                                    WHERE `student_name` = '$student_name_value'");

                        ?>
                        <tr>
                            <td><?php echo $x; ?></td>
                            <td style="<?php if ($isExist > 0) { echo "text-decoration: line-through"; }?>"><?php echo $student_name_value; ?></td>
                            <td>
                                <button
                                    class="btn <?php
                                    if ($isExist > 0) {
                                        echo 'btn-danger';
                                    } else {
                                        echo 'btn-primary';
                                    }?>"
                                    <?php if ($isExist > 0) { echo 'disabled'; }?>
                                    data-bs-toggle="modal"
                                    data-bs-target="#requestOrderModal<?php echo $x;?>">request order</button>
                            </td>

                            <!-- Modal requestOrder -->
                            <div class="modal fade" id="requestOrderModal<?php echo $x?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="<?php echo '?page=1&school_id='.$_GET['school_id'];?>&grade=<?php echo $_GET['grade'];?>&semester=<?php echo $_GET['semester']; ?>&student_name=<?php echo $_GET['student_name']; ?>&sitting_number=<?php echo $_GET['sitting_number'];?>&submit=; ?>" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">request order</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="modalBody1">
                                                <input type="hidden" name="id" value="<?php echo $x; ?>">
                                                <div class="content-modal">
                                                    <!--                                                    <p>item price <span class="text-danger">--><?php //echo '';?><!--</span></p>-->
                                                    <p>Are you sure to create the request?</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="order_submit" class="btn btn-primary">request order</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </tr>
                    <?php $x++; } ?>
                </tbody>
            </table>


            <nav class="d-flex justify-content-center" aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a
                        <?php ($data_pagination['currentPage'] == $data_pagination['firstPage'] ? print 'disabled="disabled"' : '')?> class="page-link" href="?page=<?php echo $data_pagination['firstPage'] ?>&school_id=<?php echo $_GET['school_id'];?>&grade=<?php echo $_GET['grade'];?>&semester=<?php echo $_GET['semester']; ?>&student_name=<?php echo $_GET['student_name']; ?>&sitting_number=<?php echo $_GET['sitting_number'];?>&submit=" tabindex="-1" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Links of the pages with page number -->
                <?php for($i = $data_pagination['start']; $i <= $data_pagination['end']; $i++) { ?>
                    <li class='page-item <?php ($i == $data_pagination['currentPage'] ? print 'active' : '')?>'>
                        <a class='page-link'
                           href='?page=<?php echo $i?>&school_id=<?php echo $_GET['school_id'];?>&grade=<?php echo $_GET['grade'];?>&semester=<?php echo $_GET['semester']; ?>&student_name=<?php echo $_GET['student_name']; ?>&sitting_number=<?php echo $_GET['sitting_number'];?>&submit='
                        ><?php echo $i;?></a>
                    </li>
                <?php } ?>

                <li class="page-item">
                    <a <?php ($data_pagination['currentPage'] >= $data_pagination['total_pages'] ? print 'disabled="disabled"' : '')?> class="page-link" href="?page=<?php echo $data_pagination['lastPage'] ?>&school_id=<?php echo $_GET['school_id'];?>&grade=<?php echo $_GET['grade'];?>&semester=<?php echo $_GET['semester']; ?>&student_name=<?php echo $_GET['student_name']; ?>&sitting_number=<?php echo $_GET['sitting_number'];?>&submit=" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php } ?>
    </div>
</div>
<!--  End main    -->

<!-- Modal search -->
<div class="modal fade" id="searchStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">custom search</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="modal-body" id="modalBody">
                    <div class="content-modal">
                        <input type="text"
                               name="student_name"
                               value="<?php echo $_SESSION['student_name_order']; ?>"
                               class="form-control mb-2" placeholder="search by name">

                        <input
                                type="number"
                                name="sitting_number"
                                class="form-control mb-2"
                                value="<?php echo $_SESSION['sitting_number_order']; ?>"
                                placeholder="search by sitting_number">
                        <input
                                type="text"
                                name="grade"
                                class="form-control mb-2"
                                value="<?php echo $_SESSION['grade_order']; ?>"
                                placeholder="search by grade">
                        <select name="school_id" class="form-select mb-2" aria-label="Default select example">
                            <?php foreach (getRows('school') as $school) { ?>
                                <option
                                        value="<?php echo $school['id']; ?>"
                                    <?php if (($school['id'] === $_SESSION['school_id_order']) || empty($_SESSION['school_id_order'])) {echo 'selected';} ?>
                                > <?php echo $school['school_name'];?></option>
                            <?php } ?>
                        </select>
                        <select name="semester" class="form-select mb-2" aria-label="Default select example">
                            <option value="الفصل الدراسي الاول"  <?php if (('الفصل الدراسي الاول' === $_SESSION['semester_order']) || empty($_SESSION['semester_order'])) { echo 'selected'; } ?>>الفصل الدراسي الاول</option>
                            <option value="الفصل الدراسي الثانى"  <?php if ('الفصل الدراسي الثانى' === $_SESSION['semester_order']) { echo 'selected'; } ?>>الفصل الدراسي الثانى</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">search</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    require BLP . 'shared/footer.php';
?>