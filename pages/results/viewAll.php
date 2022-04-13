<?php
    require './../../config.php';

    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';

    function sqlQueries($limit, $student_name, $sitting_number, $semester, $school_id) {
        $conditionCount = "
            WHERE 
            `student_name` LIKE '%$student_name%' AND
            `sitting_number` LIKE '%$sitting_number%' AND
            `semester` LIKE '%$semester%' AND
            `schoolId` LIKE '%$school_id%'
            ";
        $conditionOrRestSql = "
                LEFT JOIN school ON result.schoolId = school.id
                LEFT JOIN subject ON result.subjectId = subject.id
                WHERE 
                `student_name` LIKE '%$student_name%' AND
                `sitting_number` LIKE '%$sitting_number%' AND
                `semester` LIKE '%$semester%' AND
                `schoolId` LIKE '%$school_id%' 
            ";

        return pagination('result', $limit, $conditionCount, $conditionOrRestSql);
    }

    $student_name = NULL;
    $sitting_number = NULL;
    $semester = NULL;
    $school_id = NULL;


    $x=1;
    $limit = 5;

    $data_pagination = sqlQueries($limit, $student_name, $sitting_number, $semester, $school_id);

    if (isset($_POST['submit'])) {
        $student_name = sanitizeString($_POST['student_name']);
        $sitting_number = sanitizeInteger($_POST['sitting_number']);
        $semester = sanitizeString($_POST['semester']);
        $school_id = $_POST['school_id'];

        if ($school_id == 'NULL') {
            $school_id = NULL;
        }

        $data_pagination = $data_pagination = sqlQueries($limit, $student_name, $sitting_number, $semester, $school_id);;
    }
?>

<!--  start main    -->
<div class="main" id="main">
    <div class="resultsViewAll">

        <div class="search">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#searchModal">custom search</button>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">student_name</th>
                <th scope="col">semester</th>
                <th scope="col">grade</th>
                <th scope="col">school_year</th>
                <th scope="col">sitting_number</th>
                <th scope="col">degree</th>
                <th scope="col">name subject</th>
                <th scope="col">name school</th>
                <th scope="col">options</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($data_pagination['data'] as $row) {  ?>
                    <tr>
                        <th scope="row"><?php echo $x; ?></th>
                        <td><?php echo $row['student_name']; ?></td>
                        <td><?php echo $row['semester']; ?></td>
                        <td><?php echo $row['grade']; ?></td>
                        <td><?php echo $row['school_year']; ?></td>
                        <td><?php echo $row['sitting_number']; ?></td>
                        <td><?php echo $row['degree']; ?></td>
                        <td><?php echo $row['subject_name']; ?></td>
                        <td><?php echo $row['school_name']; ?></td>
                        <td>
                            <button class="btn btn-primary">edit</button>
                            <button class="btn btn-danger">delete</button>
                        </td>
                    </tr>
                <?php $x++; } ?>
            </tbody>
        </table>

        <nav class="d-flex justify-content-center" aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a <?php ($data_pagination['currentPage'] == $data_pagination['firstPage'] ? print 'disabled="disabled"' : '')?> class="page-link" href="?page=<?php echo $data_pagination['firstPage'] ?>" tabindex="-1" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Links of the pages with page number -->
                <?php for($i = $data_pagination['start']; $i <= $data_pagination['end']; $i++) { ?>
                    <li class='page-item <?php ($i == $data_pagination['currentPage'] ? print 'active' : '')?>'>
                        <a class='page-link' href='?page=<?php echo $i;?>'><?php echo $i;?></a>
                    </li>
                <?php } ?>

                <li class="page-item">
                    <a <?php ($data_pagination['currentPage'] >= $data_pagination['total_pages'] ? print 'disabled="disabled"' : '')?> class="page-link" href="?page=<?php echo $data_pagination['lastPage'] ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!--  End main    -->

<!-- Modal search -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">custom search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="content-modal">
                        <input type="text" name="student_name" class="form-control mb-2" placeholder="search by name">
                        <input type="text" name="semester" class="form-control mb-2" placeholder="search by semester">
                        <input type="number" name="sitting_number" class="form-control mb-2" placeholder="search by sitting_number">
                        <select name="school_id" class="form-select mb-2" aria-label="Default select example">
                            <option selected value="NULL">choose school</option>
                            <?php foreach (getRows('school') as $school) { ?>
                                <option value="<?php echo $school['id']; ?>"><?php echo $school['school_name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit" type="button" class="btn btn-primary">search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
    require BLP . 'shared/footer.php';
?>