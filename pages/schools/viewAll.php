<?php
    require '../../config.php';
    require BLP . 'shared/header.php';
?>

<?php
    unset($_SESSION["school_name"]);

    $x=1;
    $limit = 5;
    $school_name = NULL;
    $conditionCount = "WHERE school_name LIKE '%$school_name%'";
    $conditionOrRestSql = "WHERE school_name LIKE '%$school_name%'";

    $data_pagination = pagination('school', $limit, $conditionCount, $conditionOrRestSql);

    if (isset($_POST['submit'])) {
        $school_name = $_POST['school_name'];
        $_SESSION['school_name'] = $school_name;

        $conditionCount = "WHERE school_name LIKE '%$school_name%'";
        $conditionOrRestSql = "WHERE school_name LIKE '%$school_name%'";

        $data_pagination = pagination('school', $limit, $conditionCount, $conditionOrRestSql);
    }


?>

<!--  start main    -->
<div class="main" id="main">
    <div class="schoolsViewAll">
        <div class="search">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="d-flex justify-content-between flex-wrap">
                    <div>
                        <input
                                type="text"
                                class="form-control mb-2"
                                name="school_name"
                                value="<?php echo $_SESSION['school_name']; ?>"
                                placeholder="search by school name">
                    </div>

                    <div>
                        <button
                                class="btn btn-primary"
                                type="submit"
                                name="submit">search</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">name</th>
                    <th scope="col">address</th>
                    <th scope="col">options</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($data_pagination['data'] as $row){  ?>
                    <tr>
                        <th scope="row"><?php echo $x; ?></th>
                        <td><?php echo $row['school_name']; ?></td>
                        <td><?php echo $row['school_address']; ?></td>
                        <td>
                            <a href="<?php echo BASEURLPAGES.'schools/edit.php?id='.$row['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="<?php echo BASEURLPAGES . 'schools/delete.php?id='.$row['id']; ?>" id="delete" class="btn btn-danger delete" >Delete</a>
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
<?php

    require BLP . 'shared/footer.php';
?>