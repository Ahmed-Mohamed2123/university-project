<?php
    require '../../config.php';
    require BLP.'shared/header.php';
    require BL.'utils/validate.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    if (isset($_POST['submit'])) {
        $sitting_number = $_POST['sitting_number'];
        $schoolId = $_POST['schoolId'];
        $getStudentName = getRow("SELECT DISTINCT(`student_name`), `schoolId` FROM `result` WHERE result.sitting_number = $sitting_number AND result.schoolId = $schoolId");
        $student_name = $getStudentName['student_name'];

        $resultSql = "SELECT `subject_name`, `degree`, `max_degree`, `min_degree`, result.id, `student_name` FROM `result` LEFT JOIN subject ON result.subjectId = subject.id WHERE `student_name` = '$student_name' AND `schoolId` = $schoolId";
        $subject_data[] = getResults($resultSql);
        $modified_student_name = sanitizeString($_POST['student_name']);
        foreach ($subject_data[0] as $row) {
            if ($student_name != $modified_student_name) {
                $sql_insertName = "UPDATE `result` SET `student_name` = '$modified_student_name' WHERE `student_name`= '$student_name' AND `schoolId` = $schoolId";
                $result = db_insert($sql_insertName);
                if ($result['boolean'] === true) {
                    $success_message = 'تم تعديل اسم الطالب بنجاح';
                } else {
                    $error_message = $result['message'];
                }
            }
            // get modified degree only
            if ($_POST[$row['subject_name']] !== $row['degree']) {
                $modified_degree[] = [
                    $row['subject_name'] => $_POST[$row['subject_name']]
                ];
                $key = $row['subject_name'];
                $value = $_POST[$row['subject_name']];
                $subjects_id = $row['id'];
                $sql_insert = "UPDATE `result` SET `degree` = $value WHERE `id`= $subjects_id AND `schoolId` = $schoolId";
                $result = db_insert($sql_insert);

                if ($result['boolean'] === true) {
                    $success_message = $result['message'];
                    header("refresh:2;url=" . BASEURLPAGES . '/results/viewAll.php');
                } else {
                    $error_message = $result['message'];
                }
            }
        }

    }

    require BL . 'utils/error.php';
    require BLP.'shared/footer.php';

