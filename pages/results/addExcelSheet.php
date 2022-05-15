<?php
require '../../config.php';

if (isset($_POST['submit_file'])) {


}

$subjects = getRows('subject');
$schools = getRows('school');
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
            $isLoading = true;
            require BLP . 'shared/loading.php';
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
            var_dump(true);

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
                                echo json_encode($resultInsertResult['message']);

                            } else {
                                unlink(BL . 'uploads/' . $_FILES['uploadFile']['name']);
                                echo json_encode($resultInsertResult['message']);
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

require BLP . 'shared/footer.php';