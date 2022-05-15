<?php
    require '../../config.php';
    require BL . 'utils/validate.php';
    require BLP . 'shared/header.php';

    $x=0;
    $limit = 5;
    $role = intval($_SESSION['role']);
    $profileId = intval($_SESSION['id']);

    if ($role === 0) {
        $conditionCount = "
                    WHERE `parentId` LIKE '%$profileId%'
                ";

        $conditionOrRestSql = "
                    LEFT JOIN `result` ON result.id = _order.resultId
                    LEFT JOIN `parent` ON parent.id = _order.parentId
                    WHERE `parentId` LIKE '%$profileId%'
                ";
    } else {
        $conditionCount = "
                    LEFT JOIN `result` ON result.id = _order.resultId
                ";

        $conditionOrRestSql = "
                    LEFT JOIN `parent` ON parent.id = _order.parentId
                    LEFT JOIN `result` ON result.id = _order.resultId
                ";
    }



    $data_pagination = pagination(
            '_order.id,
             _order.order_date, 
             _order.price, 
             _order.invoiceId,
             _order.resultId,
             _order.order_status,
             _order.parentId,
             parent.username,
             result.student_name', '_order', $limit, $conditionCount, $conditionOrRestSql);
?>

<!--  start main    -->
<div class="main" id="main">
    <div class="ordersViewAll">
        <table class="table table-hover" style="cursor: pointer">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <?php if ($role === 0) { ?>
                    <th scope="col">order status</th>
                    <?php } ?>
                    <th scope="col">order date</th>
                    <th scope="col">price</th>
                    <th scope="col">name</th>
                    <?php if ($role === 0) { ?>
                        <th scope="col">info</th>
                    <?php } ?>
                    <th scope="col">options</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_pagination['data'] as $row) {  ?>
                    <tr>
                        <td><?php echo $x; ?></td>
                        <?php if ($role === 0) { ?>
                            <td>
                                <?php
                                    if (intval($row['order_status']) === 0) {
                                        echo 'please waiting <img src=' . ASSETS . 'images/clock.png' . ' />';
                                    } elseif (intval($row['order_status']) === 1) {
                                        echo 'accepted <img style="width:26px;height:26px" src=' . ASSETS . 'images/accepted.png' . ' />';
                                    } elseif (intval($row['order_status']) === 2) {
                                        echo 'rejected <img style="width:26px;height:26px" src=' . ASSETS . 'images/rejected.png' . ' />';
                                    } elseif (intval($row['order_status']) === 3) {
                                        echo 'accepted <img style="width:26px;height:26px" src=' . ASSETS . 'images/accepted.png' . ' />';
                                    }
                                ?>
                            </td>
                        <?php } ?>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <?php if ($role === 0) { ?>
                            <td><?php echo $row['student_name']; ?></td>
                        <?php } elseif ($role === 1) { ?>
                            <td><?php echo $row['username']; ?></td>
                        <?php } ?>

                        <?php if ($role === 0) { ?>
                            <td>
                                <?php
                                    if (intval($row['order_status']) === 0) {
                                        echo 'The order may take a few hours, please wait.';
                                    } elseif (intval($row['order_status']) === 1) {
                                        echo 'Please complete the rest of the processes to get the required.';
                                    } elseif (intval($row['order_status']) === 2) {
                                        echo 'See the administration to find out the reason for the refusal.';
                                    } elseif (intval($row['order_status']) === 3) {
                                        echo 'All operations have been completed. You can view what you requested.';
                                    }
                                ?>
                        </td>
                        <?php } ?>
                        <td>
                            <!--  for parent  -->
                            <?php if ($role === 0 && intval($row['order_status']) === 0) { ?>
                                <a class="btn btn-danger" href="<?php echo BASEURLPAGES . 'orders/delete.php?id=' . $row['id'];?>">delete</a>
                            <?php } ?>
                            <?php if ($role === 0 && intval($row['order_status']) === 1) { ?>
                                <a class="btn btn-primary" href="<?php echo BASEURLPAGES . 'orders/checkout.php?id=' . $row['id'] . '&price=' . $row['price'];?>">checkout</a>
                            <?php } ?>
                            <?php if ($role === 0 && intval($row['order_status']) === 3) { ?>
                                <a class="btn btn-primary" href="<?php echo BASEURLPAGES . 'orders/orderDetails.php?id=' . $row['id'];?>">open</a>
                            <?php } ?>
                            <!--  for employee  -->
                            <?php if ($role === 1) { ?>
                                <?php if (
                                        intval($row['order_status']) === 1 ||
                                        intval($row['order_status']) === 0 ||
                                        intval($row['order_status']) === 2) { ?>
                                    <a
                                            class="btn btn-primary"
                                            <?php if (intval($row['order_status']) === 1) {echo 'disabled="disabled"';} ?>
                                            href="<?php echo BASEURLPAGES . 'orders/changeStatusOrder.php?request=accept&id=' . $row['id'];?>">accept</a>
                                    <a
                                            class="btn btn-danger"
                                            <?php if (intval($row['order_status']) === 2) {echo 'disabled="disabled"';} ?>
                                            href="<?php echo BASEURLPAGES . 'orders/changeStatusOrder.php?request=reject&id=' . $row['id'];?>">reject</a>
                                <?php } ?>
                            <?php }?>
                            </td>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="modalBody">
                                        <div class="content-modal">
                                            <h3>Invoice</h3>
                                            <hr>
                                            <div class="invoice-info">
                                                <div class="item d-flex justify-content-between">
                                                    <p class="lead">amount:_</p>
                                                    <p>10</p>
                                                </div>
                                                <div class="item d-flex justify-content-between">
                                                    <p class="lead">invoice.invoice_date:_</p>
                                                    <p>11/2/2022</p>
                                                </div>
                                                <div class="item d-flex justify-content-between">
                                                    <p class="lead">name parent:_</p>
                                                    <p>Ahmed Mohamed</p>
                                                </div>
                                                <div class="item d-flex justify-content-between">
                                                    <p class="lead">address</p>
                                                    <p>mansoura</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="invoice_total d-flex justify-content-between">
                                                <p class="lead">invoice_total</p>
                                                <p class="fw-bold">50</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="printInvoice(this)">print</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                <?php $x++; } ?>
            </tbody>
        </table>

        <nav class="d-flex justify-content-center" aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a <?php ($data_pagination['currentPage'] == $data_pagination['firstPage'] ? print 'disabled="disabled"' : '')?>
                            class="page-link"
                            href="?page=<?php echo $data_pagination['firstPage'] ?>" tabindex="-1" aria-label="Previous">
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
                    <a <?php ($data_pagination['currentPage'] >= $data_pagination['total_pages'] ? print 'disabled="disabled"' : '')?>
                            class="page-link"
                            href="?page=<?php echo $data_pagination['lastPage'] ?>" aria-label="Next">
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