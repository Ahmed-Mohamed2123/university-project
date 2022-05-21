<?php
    require './../../config.php';
    require BLP . 'shared/header.php';

    if ($_SESSION['role'] === '0') {
        header('location:' . BASEURLPAGES . 'index.php');
    } elseif (!isset($_SESSION['role'])) {
        header('location:' . BASEURLPAGES . 'auth/login.php');
    }

    unset($_SESSION["usernameParent"]);

    $x=1;
    $limit = 5;
    $username = $_GET['username'];
    $_SESSION['usernameParent'] = $username;
    $conditionCount = "WHERE username LIKE '%$username%'";
    $conditionOrRestSql = "WHERE username LIKE '%$username%'";

    $data_pagination = pagination('*','parent', 5,$conditionCount, $conditionOrRestSql);


?>

<!--  start main    -->
<div class="main" id="main parent">
    <div class="parentsViewAll">
        <div class="search">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
                <input type="hidden" name="page" value="1">
                <div class="d-flex justify-content-between flex-wrap">
                    <div>
                        <input
                                type="text"
                                class="form-control mb-2"
                                name="username"
                                value="<?php echo $_SESSION['usernameParent']; ?>"
                                placeholder="search by username">
                    </div>

                    <div>
                        <button
                                class="btn btn-primary"
                                type="submit">ابحث</button>
                    </div>
                </div>
            </form>
        </div>

        <div style="overflow-y: auto">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">الاسم</th>
                    <th scope="col">العنوان</th>
                    <th scope="col">الرقم القومى</th>
                    <th scope="col">options</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data_pagination['data'] as $row) {  ?>
                    <tr>
                        <th scope="row"><?php echo $x; ?></th>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['national_id']; ?></td>
                        <td>
                            <a href="<?php echo BASEURLPAGES.'parents/edit.php?id='.$row['id']; ?>" class="btn btn-primary">تعديل</a>
                            <a href="<?php echo BASEURLPAGES . 'parents/delete.php?id='.$row['id']; ?>" id="delete" class="btn btn-danger delete" >حذف</a>
                        </td>
                    </tr>
                    <?php $x++; } ?>
                </tbody>
            </table>
        </div>

        <nav class="d-flex justify-content-center" aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a <?php ($data_pagination['currentPage'] == $data_pagination['firstPage'] ? print 'disabled="disabled"' : '')?>
                            class="page-link"
                            href="?page=<?php echo $data_pagination['firstPage'] ?>&username=<?php echo $username; ?>" tabindex="-1" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Links of the pages with page number -->
                <?php for($i = $data_pagination['start']; $i <= $data_pagination['end']; $i++) { ?>
                    <li class='page-item <?php ($i == $data_pagination['currentPage'] ? print 'active' : '')?>'>
                        <a class='page-link'
                           href='?page=<?php echo $i;?>&username=<?php echo $username; ?>'>
                            <?php echo $i;?>
                        </a>
                    </li>
                <?php } ?>

                <li class="page-item">
                    <a <?php ($data_pagination['currentPage'] >= $data_pagination['total_pages'] ? print 'disabled="disabled"' : '')?>
                            class="page-link"
                            href="?page=<?php echo $data_pagination['lastPage'] ?>&username=<?php echo $username; ?>" aria-label="Next">
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