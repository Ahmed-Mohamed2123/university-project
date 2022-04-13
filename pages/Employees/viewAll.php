<?php
    require './../../config.php';
    require BLP . 'shared/header.php';

    unset($_SESSION["employeeUsername"]);

    $x=1;
    $limit = 5;

    $username = $_GET['username'];
    $_SESSION['employeeUsername'] = $username;
    $conditionCount = "WHERE username LIKE '%$username%'";
    $conditionOrRestSql = "WHERE username LIKE '%$username%'";
    $data_pagination = pagination('employee', $limit ,$conditionCount, $conditionOrRestSql);

?>

    <!--  start main    -->
    <div class="main" id="main">
        <div class="parentsViewAll">
            <div class="search">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div>
                            <input type="hidden" name="page" value="<?php echo ($_GET['page'] === NULL ? 1 : $_GET['page']);?>">
                            <input
                                    type="text"
                                    class="form-control mb-2"
                                    name="username"
                                    value="<?php echo $_SESSION['employeeUsername']; ?>"
                                    placeholder="search by username">
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
                    <th scope="col">username</th>
                    <th scope="col">address</th>
                    <th scope="col">national_id</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data_pagination['data'] as $row) {  ?>
                    <tr>
                        <th scope="row"><?php echo $x; ?></th>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['national_id']; ?></td>
                    </tr>
                    <?php $x++; } ?>
                </tbody>
            </table>

            <nav class="d-flex justify-content-center" aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a <?php ($data_pagination['currentPage'] == $data_pagination['firstPage'] ? print 'disabled="disabled"' : '')?> class="page-link" href="?page=<?php echo $data_pagination['firstPage']?>&username=<?php echo $_GET['username'] ?>" tabindex="-1" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Links of the pages with page number -->
                    <?php for($i = $data_pagination['start']; $i <= $data_pagination['end']; $i++) { ?>
                        <li class='page-item <?php ($i == $data_pagination['currentPage'] ? print 'active' : '')?>'>
                            <a class='page-link' href='?page=<?php echo $i;?>&username=<?php echo $_GET['username']; ?>'><?php echo $i;?></a>
                        </li>
                    <?php } ?>

                    <li class="page-item">
                        <a <?php ($data_pagination['currentPage'] >= $data_pagination['total_pages'] ? print 'disabled="disabled"' : '')?> class="page-link" href="?page=<?php echo $data_pagination['lastPage']?>&username=<?php echo $_GET['username']; ?>" aria-label="Next">
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