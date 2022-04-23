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
        $getStudentName = getRow("SELECT DISTINCT(`student_name`) FROM `result` WHERE result.sitting_number = $sitting_number");
        $student_name = $getStudentName['student_name'];

        $resultSql = "SELECT `subject_name`, `degree`, `max_degree`, `min_degree`, result.id FROM `result` LEFT JOIN subject ON result.subjectId = subject.id WHERE `student_name` = '$student_name'";
        $subject_data[] = getResults($resultSql);

        $modified_student_name = sanitizeString($_POST['student_name']);
        foreach ($subject_data[0] as $row) {
            // get modified degree only
            if ($_POST[$row['subject_name']] !== $row['degree']) {
                $modified_degree[] = [
                    $row['subject_name'] => $_POST[$row['subject_name']]
                ];
                $key = $row['subject_name'];
                $value = $_POST[$row['subject_name']];
                $subjects_id = $row['id'];
                $sql_insert = "UPDATE `result` SET `degree` = $value WHERE `id`= $subjects_id ";

                $result = db_insert($sql_insert);
                if ($result['boolean'] === true) {
                    $success_message = $result['message'];
                    header("refresh:2;url=" . BASEURLPAGES . '/results/viewParentOrder.php');
                } else {
                    $error_message = $result['message'];
                }
                require BL . 'utils/error.php';
            }
        }

    }


    require BLP.'shared/footer.php';

