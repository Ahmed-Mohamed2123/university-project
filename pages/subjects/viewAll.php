<?php
    require '../../config.php';
    require BLP . 'shared/header.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    unset($_SESSION["subject_name"]);

    $x = 1;
    $limit = 5;

    $subject_name = $_GET['subject_name'];
    $_SESSION['subject_name'] = $subject_name;
    $conditionCount = "WHERE subject_name LIKE '%$subject_name%'";
    $conditionOrRestSql = "WHERE subject_name LIKE '%$subject_name%'";
    $data_pagination = pagination('*','subject', $limit,$conditionCount, $conditionOrRestSql);
?>
<!--  start main    -->
<div class="main" id="main">
    <div class="subjectsViewAll">
        <div class="search">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
                <input type="hidden" name="page" value="1">
                <div class="d-flex justify-content-between flex-wrap">
                    <div>
                        <input
                                type="text"
                                class="form-control mb-2"
                                name="subject_name"
                                value="<?php echo $_SESSION['subject_name']; ?>"
                                placeholder="search by subject name">
                    </div>

                    <div>
                        <button
                                class="btn btn-primary"
                                type="submit">search</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">subject name</th>
                    <th scope="col">options</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_pagination['data'] as $row){  ?>
                    <tr>
                        <th scope="row"><?php echo $x; ?></th>
                        <td><?php echo $row['subject_name']; ?></td>
                        <td>
                            <a href="<?php echo BASEURLPAGES.'subjects/edit.php?id='.$row['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="<?php echo BASEURLPAGES . 'subjects/delete.php?id='.$row['id']; ?>" id="delete" class="btn btn-danger delete" >Delete</a>
                        </td>
                    </tr>
                <?php $x++; } ?>
            </tbody>
        </table>

        <nav class="d-flex justify-content-center" aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a <?php ($data_pagination['currentPage'] == $data_pagination['firstPage'] ? print 'disabled="disabled"' : '')?>
                            class="page-link"
                            href="?page=<?php echo $data_pagination['firstPage'] ?>&subject_name=<?php echo $subject_name?>" tabindex="-1" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Links of the pages with page number -->
                <?php for($i = $data_pagination['start']; $i <= $data_pagination['end']; $i++) { ?>
                    <li class='page-item <?php ($i == $data_pagination['currentPage'] ? print 'active' : '')?>'>
                        <a class='page-link'
                           href='?page=<?php echo $i;?>&subject_name=<?php echo $subject_name; ?>'><?php echo $i;?></a>
                    </li>
                <?php } ?>

                <li class="page-item">
                    <a <?php ($data_pagination['currentPage'] >= $data_pagination['total_pages'] ? print 'disabled="disabled"' : '')?>
                            class="page-link"
                            href="?page=<?php echo $data_pagination['lastPage'] ?>&subject_name=<?php echo $subject_name; ?>" aria-label="Next">
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