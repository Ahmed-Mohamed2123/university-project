<?php
    require '../../config.php';
    require BLP.'shared/header.php';
    require BL.'utils/validate.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    if(isset($_GET['sitting_number']) && is_numeric($_GET['sitting_number']))
    {
        $sitting_number = $_GET['sitting_number'];

        $student_name = getRow("SELECT DISTINCT(`student_name`) FROM `result` WHERE result.sitting_number = $sitting_number")['student_name'];
        $resultSql = "SELECT result.id FROM `result` WHERE `student_name` = '$student_name'";
        $subject_data = getResults($resultSql);

        foreach ($subject_data as $row) {
            $resultId = $row['id'];
            $sqlDelete =  "DELETE FROM `result` WHERE `id`= $resultId";
            $result = deleteRow($sqlDelete);
            if ($result['boolean'] === true) {
                $success_message = $result['message'];

                header( "refresh:3;url=".BASEURLPAGES."results/viewAll.php");
            } else {
                $error_message = $result['message'];
            }
        }




        require BL.'utils/error.php';
    }

    require BLP.'shared/footer.php';

