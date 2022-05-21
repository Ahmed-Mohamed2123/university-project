<?php

$server = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'university-project';

$conn = mysqli_connect($server, $username, $password, $dbname);

if (!$conn) {
    die("Error In Connection: " . mysqli_connect_error());
}

function db_insert($sql) {
    // I used global, because I want use $conn in this scope function
    global $conn;

    $result = mysqli_query($conn, $sql);
    if ($result) {
        return [
            'id' => intval($conn->insert_id),
            'boolean' => true,
            'message' => 'تمت اضافه البيانات بنجاح'
        ];
    }

    return [
        'boolean' => false,
        'message' => 'حدث خطأ , حاول مره اخرى'
    ];
}

// update existing record
function db_update($sql)
{
    global $conn;
    $result = mysqli_query($conn,$sql);
    if($result)
    {
        return [
            'boolean' => true,
            'message' => 'تم تحديث البيانات بنجاح'
        ];
    }
    return [
        'boolean' => false,
        'message' => 'حدث خطأ , حاول مره اخرى'
    ];
}

// delete record
function deleteRow($sql)
{
    global $conn;
    $result = mysqli_query($conn,$sql);
    if($result)
    {
        return [
            'boolean' => true,
            'message' => 'تم حذف البيانات بنجاح'
        ];
    }
    return [
        'boolean' => false,
        'message' => 'حدث خطأ , حاول مره اخرى',
    ];
}

function getCount($table) {
    global $conn;
    $sql = "SELECT COUNT(*) as 'total_rows' FROM `$table` ";
    $result = mysqli_fetch_object(mysqli_query($conn, $sql));
    return $result->total_rows;
}

function getRows($table) {
    global $conn;
    $sql = "SELECT * FROM `$table` ";

    $result = mysqli_query($conn, $sql);
    if(!$result)
    {
        echo mysqli_error($conn);
    }
    $rows = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result))
        {
            $rows[] = $row;
        }

    }
    return $rows;
}

function getRow($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $rows = [];
        if (mysqli_num_rows($result) > 0) {
            $rows[] = mysqli_fetch_assoc($result);
            return $rows[0];
        }

    }
    return false;
}


function getCountRows($itemsCount, $table, $condition) {
    global $conn;
    $sql = "SELECT COUNT($itemsCount) AS `count` FROM `$table` $condition";
    $result = mysqli_query($conn, $sql);
    $res = mysqli_fetch_assoc($result);
    return $res['count'];
}

// For all pages
function pagination($selectItem, $table, $limit, $conditionCount, $conditionOrRestSql) {
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = $_GET['page'];
    } else {
        $currentPage = 1;
    }
    $startFrom = ($currentPage * $limit) - $limit;
    // fetch data with specific limit
    // LIMIT offset, count
    $tableSQL = "SELECT $selectItem FROM `$table` $conditionOrRestSql LIMIT $startFrom,$limit";
    return paginationOperations("*", $table, $limit, $conditionCount, $conditionOrRestSql, $tableSQL, $currentPage);
}

// for result page
function paginationResult($table, $limit, $conditionCount, $conditionOrRestSql) {
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = $_GET['page'];
    } else {
        $currentPage = 1;
    }
    // Suppose $currentPage = 2 and $limit = 4 =>>>> 2*4 - 4 = 4
    // $startFrom => offset
    $startFrom = ($currentPage * $limit) - $limit;
    // fetch data with specific limit
    // LIMIT offset, count
    $tableSQL = "SELECT DISTINCT(`student_name`), `semester`, `grade`, `school_year`, `sitting_number`, `school_name` FROM `$table` $conditionOrRestSql LIMIT $startFrom,$limit";
    return paginationOperations("DISTINCT `student_name`", $table, $limit, $conditionCount, $conditionOrRestSql, $tableSQL, $currentPage);
}

// pagination order
function paginationOrder($table, $limit, $conditionCount, $conditionOrRestSql) {
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = $_GET['page'];
    } else {
        $currentPage = 1;
    }
    $startFrom = ($currentPage * $limit) - $limit;
    // fetch data with specific limit
    // LIMIT offset, count
    $tableSQL = "SELECT DISTINCT(`student_name`), `schoolId` FROM `$table` $conditionOrRestSql LIMIT $startFrom,$limit";
    return paginationOperations("DISTINCT `student_name`", $table, $limit, $conditionCount, $conditionOrRestSql, $tableSQL, $currentPage);
}

function paginationOperations($itemsCount, $table, $limit, $conditionCount, $conditionOrRestSql, $data_sql, $currentPage) {
    global $conn;
    $totalRows = getCountRows($itemsCount, $table, $conditionCount);

    // ceil(1.4) => 1 &&& ceil(0.7) => 1
    $totalPages = ceil($totalRows / $limit);

    $lastPage = ceil($totalRows / $limit);
    $firstPage = 1;

    $nextPage = $currentPage + 1;
    $previousPage = $currentPage - 1;

    // fetch data with specific limit
    // LIMIT offset, count
    $result = mysqli_query($conn, $data_sql);
    $data = [];

    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }

    /* display pages link */
    $adjacent = 2;
    if($totalPages <= (1 + ($adjacent * 2))) {
        $start = 1;
        $end   = $totalPages;
    } else {
        if(($currentPage - $adjacent) > 1) {
            if(($currentPage + $adjacent) < $totalPages) {
                $start = ($currentPage - $adjacent);
                $end   = ($currentPage + $adjacent);
            } else {
                $start = ($totalPages - (1+($adjacent*2)));
                $end   = $totalPages;
            }
        } else {
            $start = 1;
            $end   = (1+($adjacent * 2));
        }
    }

    return [
        'currentPage' => $currentPage,
        'previousPage' => $previousPage,
        'firstPage' => $firstPage,
        'lastPage' => $lastPage,
        'nextPage' => $nextPage,
        'data' => $data,
        'total_pages' => $totalPages,
        'start' => $start,
        'end' => $end
    ];
}

function getStudents($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    if(!$result)
    {
        echo mysqli_error($conn);
    }
    $rows = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result))
        {
            $rows[] = $row;
        }

    }
    return $rows;
}

function getResults($sql) {
    global $conn;

    $result = mysqli_query($conn, $sql);
    if(!$result)
    {
        echo mysqli_error($conn);
    }
    $rows = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result))
        {
            $rows[] = $row;
        }

    }
    return $rows;
}

