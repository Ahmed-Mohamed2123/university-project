<?php
    use Phppot\DataSource;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
    require './../../config.php';
    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';


    $subjects = getRows('subject');
    $schools = getRows('school');

    if (isset($_POST['submit'])) {
        $student_name = sanitizeString($_POST['student_name']);
        $semester = sanitizeString($_POST['semester']);
        $grade = sanitizeString($_POST['grade']);
        $school_year = $_POST['school_year'];
        $sitting_number = sanitizeString($_POST['sitting_number']);
        $degree = sanitizeInteger($_POST['degree']);
        $max_degree = sanitizeInteger($_POST['max_degree']);
        $school_Id = sanitizeInteger($_POST['school']);
        $subject_Id = sanitizeInteger($_POST['subject']);

        $employee_id = $_SESSION['id'];
        if (
            checkEmpty($student_name) &&
            checkEmpty($semester) &&
            checkEmpty($grade) &&
            checkEmpty($school_year) &&
            checkEmpty($sitting_number) &&
            checkEmpty($degree) &&
            checkEmpty($max_degree)
        ) {

            $sql = "INSERT INTO result (
                    `student_name`,
                    `semester`,
                    `grade`,
                    `school_year`,
                    `sitting_number`,
                    `degree`,
                    `max_degree`,
                    `subjectId`,
                    `schoolId`,
                    `employeeId`) 
                    VALUES (
                            '$student_name',
                            '$semester',
                            '$grade',
                            '$school_year',
                            $sitting_number,
                            $degree,
                            $max_degree,
                            $subject_Id,
                            $school_Id,
                            $employee_id
                            )";
            var_dump($sql);
            $result = db_insert($sql);

            if ($result['boolean'] === true) {
                $success_message = $result['message'];
            } else {
                $error_message = $result['message'];
            }
        } else {
            $error_message = 'Please Fill All Field';
        }
    }


    if (isset($_POST['submit_file'])) {
        $allowedFileType = [
            'application/vnd.ms-excel',
            'text/xls',
            'text/xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
    }

    require BL . 'utils/error.php';
?>

<!--  start main    -->
<div class="main" id="main">
    <div class="add-result p-3">
        <h3 class="mb-0 text-white">Add new result</h3>
        <hr class="bg-white">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-2">
                <input
                        class="form-control"
                        type="text"
                        name="student_name"
                        placeholder="enter your student name" >
            </div>

            <div class="mb-2">
                <input
                        class="form-control"
                        type="text"
                        name="semester"
                        placeholder="enter your semester" >
            </div>

            <div class="mb-2">
                <input
                        class="form-control"
                        type="text"
                        name="grade"
                        placeholder="enter your grade" >
            </div>

            <div class="mb-2">
                <input
                        class="form-control"
                        type="date"
                        name="school_year"
                        placeholder="enter your school_year" >
            </div>

            <div class="mb-2">
                <input
                        type="number"
                        class="form-control"
                        name="sitting_number"
                        placeholder="enter your sitting number" >
            </div>

            <div class="mb-2">
                <input
                        type="number"
                        class="form-control"
                        name="degree"
                        placeholder="enter your student degree" >
            </div>

            <div class="mb-2">
                <input
                        type="number"
                        class="form-control"
                        name="max_degree"
                        placeholder="enter your subject max_degree" >
            </div>

            <select class="form-select mb-2" aria-label="add subject" name="school">
                <?php foreach ($schools as $school) { ?>
                    <option value="<?php echo $school['id']; ?>"><?php echo $school['school_name'];?></option>
                <?php } ?>
            </select>

            <select class="form-select mb-2" aria-label="add subject" name="subject">
                <?php foreach ($subjects as $subject) { ?>
                    <option value="<?php echo $subject['id']; ?>"><?php echo $subject['subject_name'];?></option>
                <?php } ?>
            </select>


            <div class="d-flex justify-content-between">
                <button
                        class="btn btn-danger"
                        name="submit"
                        type="submit">save</button>

                <button
                        class="btn btn-primary"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModal">add sheet excel</button>
            </div>

        </form>
    </div>
</div>
<!--  End main    -->

<!-- Modals -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">add sheet excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">.excel</label>
                        <input
                                class="form-control"
                                type="file"
                                id="formFile"
                                name="file"
                                accept=".xlsx, .xls, .csv">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit_file" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    require BLP . 'shared/footer.php';
?>