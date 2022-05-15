<?php
    require '../../config.php';
    require BLP . 'shared/header.php';

    $parentId = intval($_SESSION['id']);
    $limit = 10;
    $x = 0;
    $conditionCount = "
        WHERE parentId LIKE '%$parentId%'";
    $conditionOrRestSql = "
        LEFT JOIN parent ON parent.id = payment.parentId
        WHERE parentId LIKE '%$parentId%'";

    $data_pagination = pagination(
            'payment.id, payment.paymentMethod, payment.amount, payment.date, parent.username, parent.address',
            'payment', $limit,$conditionCount, $conditionOrRestSql);
?>

<!--  start main    -->
<div class="main" id="main">
    <div class="paymentsViewAll">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">amount</th>
                    <th scope="col">payment method</th>
                    <th scope="col">date</th>
                    <th scope="col">options</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_pagination['data'] as $row){  ?>
                    <tr>
                        <td><?php echo $x; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['paymentMethod']; ?></td>
                        <td><?php echo date( "d/m/Y", strtotime($row['date'])); ?></td>
                        <td>
                            <button
                                    class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#paymentModal">show invoice</button>
                        </td>

                        <!-- Modal -->
                        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Invoice</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="modalBody">
                                        <div class="content-modal">
                                            <div class="invoice-info">
                                                <div class="item d-flex justify-content-between">
                                                    <p class="lead">order type:_</p>
                                                    <p>result</p>
                                                </div>
                                                <div class="item d-flex justify-content-between">
                                                    <p class="lead">invoice.invoice_date:_</p>
                                                    <p><?php echo date( "d/m/Y", strtotime($row['date'])); ?></p>
                                                </div>
                                                <div class="item d-flex justify-content-between">
                                                    <p class="lead">name parent:_</p>
                                                    <p><?php echo $row['username'];?></p>
                                                </div>
                                                <div class="item d-flex justify-content-between">
                                                    <p class="lead">address</p>
                                                    <p><?php echo $row['address'];?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="invoice_total d-flex justify-content-between">
                                                <p class="lead">invoice_total</p>
                                                <p class="fw-bold"><?php echo $row['amount']?></p>
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
                        <a class='page-link'
                           href='?page=<?php echo $i;?>'><?php echo $i;?></a>
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