<?php

    require './../../config.php';
    require BLP . 'shared/header.php';
    require BL . 'utils/validate.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }


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
        $min_degree = sanitizeInteger($_POST['min_degree']);
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
            checkEmpty($max_degree) &&
            checkEmpty($min_degree)
        ) {

            $sql = "INSERT INTO result (
                    `student_name`,
                    `semester`,
                    `grade`,
                    `school_year`,
                    `sitting_number`,
                    `degree`,
                    `max_degree`,
                    `min_degree`,
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
                            $min_degree,
                            $subject_Id,
                            $school_Id,
                            $employee_id
                            )";
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
        if (isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "") {
            require_once BL . '/vendor/autoload.php';
            include BL . '/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
            $allowedExtensions = array("xls","xlsx");
            // extension => الامتداد
            $ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);
            if(in_array($ext, $allowedExtensions)) {
                $file = "../../uploads/" . $_FILES['uploadFile']['name'];
                $isUploaded = copy($_FILES['uploadFile']['tmp_name'], $file);
                if($isUploaded) {
                    try {
                        // load uploaded file and get data in this file excel
                        $objPHPExcel = PHPExcel_IOFactory::load($file);
                    } catch (Exception $e) {
                        die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage());
                    }
                    // page one in Excel file
                    $sheet = $objPHPExcel->getSheet();
                    $total_rows = $sheet->getHighestRow();
                    // last of column in this file
                    $highestColumn = $sheet->getHighestColumn();
                    // total_columns
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

                    $maxCell = $sheet->getHighestRowAndColumn();
                    $main_data = $sheet->rangeToArray('A1:' . $maxCell['column'] . 5);
                    $main_data = array_map('array_filter', $main_data);
                    $main_data = array_filter($main_data);
                    $_school_name = array_values($main_data[0])[0];
                    $_grade = array_values($main_data[0])[1];
                    $_school_year = array_values($main_data[0])[3];
                    $_semester = array_values($main_data[0])[2];


                    $maxDegrees = $sheet->rangeToArray('E10:' . 'N10');
                    $minDegrees = $sheet->rangeToArray('E11:' . 'N11');
                    $subjects_names = $sheet->rangeToArray('E6:' . 'N6');

                    $numberCountDataINeedIt = $total_rows - 2;
                    $cell = $sheet->rangeToArray('E13' . ':' . $highestColumn . $total_rows,
                        NULL,
                        TRUE,
                        FALSE);
                    $val = array_map('array_filter', $cell);
                    $val = array_filter($val);

                    // range for student_name and sitting_number
                    $_range2 = $sheet->rangeToArray('B13' . ':C' . $total_rows,
                        NULL,
                        TRUE,
                        FALSE);

                    // check is exist subject in db and if it does not exist will be added to the db
                    for ($r = 0 ; $r <= count($subjects_names[0]); $r++) {
                        // I added condition because I need value of subjects_names != NULL
                        if (!empty($subjects_names[0][$r])) {
                            $values = $subjects_names[0][$r];
                        }

                        // check exist subject in db and if I does not found get this value and added in bd
                        $_sql = "SELECT `subject_name` FROM `subject` WHERE `subject_name` = '$values'";;
                        $subjectExist = getRow($_sql);
                        if (!$subjectExist) {
                            $InsertSubjectName = "INSERT INTO `subject` (`subject_name`) VALUES ('$values')";
                            db_insert($InsertSubjectName);
                        }
                    }


                    // check is exist school in db and if it does not exist will be added to the db
                    $findSchoolByName = "SELECT `school_name` FROM `school` WHERE `school_name` = '$_school_name'";
                    if (!getRow($findSchoolByName)) {
                        $insertSchoolSql = "INSERT INTO `school` (`school_name`) VALUES ('$_school_name')";
                        db_insert($insertSchoolSql);
                    }


                    $getSchoolId = "SELECT `id` from `school` WHERE `school_name` = '$_school_name'";
                    $school_id = intval(implode('',getRow($getSchoolId)));

                    for ($i = 0; $i <= count($cell); $i++) {

                        for ($l = 0 ; $l <= count($cell[$i]); $l++) {
                            $subjects_names_modify = $subjects_names[0][$l];
                            $maxDegrees_modify  = $maxDegrees[0][$l];
                            $minDegrees_modify  = $minDegrees[0][$l];
                            $val_modify = $cell[$i][$l];
                            if (
                                    !empty($_range2[$i][1]) &&
                                    !empty($val_modify) &&
                                    !empty($subjects_names_modify) &&
                                    !empty($maxDegrees_modify) &&
                                    !empty($minDegrees)) {

                                $_student_name = $_range2[$i][1];
                                $_sitting_number = $_range2[$i][0];

                                $getSubjectId = "SELECT `id` from `subject` WHERE `subject_name` = '$subjects_names_modify'";
                                $subject_id = intval(implode('',getRow($getSubjectId)));

                                $employeeId = $_SESSION['id'];

                                $_sql = "select $subjects_names_modify =>>>> $val_modify => $_student_name => $maxDegrees_modify => $minDegrees_modify => " . '<br>';
                                $studentExist = getRow("select * from result WHERE `student_name` ='$_student_name' AND `subjectId`= $subject_id");
                                if (!$studentExist) {
                                    $insertResultSql =
                                        "INSERT INTO `result`
                                        (
                                         `student_name`,
                                         `semester`,
                                         `grade`,
                                         `school_year`,
                                         `sitting_number`,
                                         `degree`,
                                         `max_degree`,
                                         `min_degree`,
                                         `subjectId`,
                                         `schoolId`,
                                         `employeeId`) VALUES (
                                                     '$_student_name',
                                                     '$_semester',
                                                     '$_grade',
                                                     '$_school_year',
                                                     $_sitting_number,
                                                     $val_modify,
                                                     $maxDegrees_modify,
                                                     $minDegrees_modify,
                                                     $subject_id,
                                                     $school_id,
                                                     $employeeId)";
                                    $resultInsertResult = db_insert($insertResultSql);

                                    if ($resultInsertResult['boolean'] === true) {
                                        unlink(BL . 'uploads/' . $_FILES['uploadFile']['name']);
                                        $success_message = $resultInsertResult['message'];

                                    } else {
                                        unlink(BL . 'uploads/' . $_FILES['uploadFile']['name']);
                                        $error_message = $resultInsertResult['message'];
                                    }
                                } else {
                                    unlink(BL . 'uploads/' . $_FILES['uploadFile']['name']);
                                }

                            }
                        }
                    }
                }
            }
        }
    }
    require BLP . 'shared/loading.php';
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
                        type="text"
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

            <div class="mb-2">
                <input
                        type="number"
                        class="form-control"
                        name="min_degree"
                        placeholder="enter your subject min_degree" >
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
            <?php if (isset($isLoading) && $isLoading === true) { ?>
                <div class="spinner-border" style="position: absolute;bottom: 20px;left: 8px;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            <?php } ?>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">add sheet excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">.xlsx</label>
                        <input
                                class="form-control"
                                type="file"
                                id="formFile"
                                name="uploadFile"
                                accept=".xlsx">
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