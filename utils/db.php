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
            'message' => 'data has bean added successfully'
        ];
    }

    return [
        'boolean' => false,
        'message' => 'An error has occurred'
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
            'message' => 'data has bean updated successfully'
        ];
    }
    return [
        'boolean' => false,
        'message' => 'An error has occurred'
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
            'message' => 'data has bean deleted successfully'
        ];
    }
    return [
        'boolean' => false,
        'message' => 'An error has occurred',
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


function getCountRows($table, $condition) {
    global $conn;
    $sql = "SELECT COUNT(*) AS `count` FROM `$table` $condition";
    $result = mysqli_query($conn, $sql);
    $res = mysqli_fetch_assoc($result);
    return $res['count'];
}

function pagination($table, $limit, $conditionCount, $conditionOrRestSql) {
    global $conn;

    $totalRows = getCountRows($table, $conditionCount);

    // If you press the next button => href="?page=1"
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = $_GET['page'];
    } else {
        $currentPage = 1;
    }

    // Suppose $currentPage = 2 and $limit = 4 =>>>> 2*4 - 4 = 4
    // $startFrom => offset
    $startFrom = ($currentPage * $limit) - $limit;
    // ceil(1.4) => 1 &&& ceil(0.7) => 1
    $totalPages = ceil($totalRows / $limit);

    $lastPage = ceil($totalRows / $limit);
    $firstPage = 1;

    $nextPage = $currentPage + 1;
    $previousPage = $currentPage - 1;

    // fetch data with specific limit
    // LIMIT offset, count
    $tableSQL = "SELECT * FROM `$table` $conditionOrRestSql LIMIT $startFrom,$limit";
    $result = mysqli_query($conn, $tableSQL);
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