<?php
    require '../../config.php';
    require BLP . 'shared/header.php';

    $order = NULL;
    $subject_data = [];
    $total = [];
    $total_max_degree = [];
    $invoiceId = NULL;
    $payment = NULL;

    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $orderId = intval($_GET['id']);
        $order = getRow("
            SELECT _order.id, _order.order_date, _order.invoiceId, _order.parentId, _order.resultId, result.student_name, parent.username, parent.address, _order.invoiceId, invoice.id FROM `_order` 
            LEFT JOIN `result` ON result.id = _order.resultId 
            LEFT JOIN `invoice` ON invoice.id = _order.invoiceId 
            LEFT JOIN `parent` ON parent.id = _order.parentId 
            WHERE _order.id = $orderId");

        $student_name = $order['student_name'];
        $resultSql = "SELECT `subject_name`, `degree`, `max_degree`, `min_degree`, result.id FROM `result` LEFT JOIN subject ON result.subjectId = subject.id WHERE `student_name` = '$student_name'";
        $subject_data[] = getResults($resultSql);

        $invoiceId = $order['invoiceId'];
        $payment = getRow("SELECT * FROM `payment` WHERE `invoiceId` = $invoiceId");

    } else {
        header('location:' . BASEURLPAGES . 'orders/viewAll.php');
    }
?>

    <div class="main" id="main">
        <div class="content">
            <div class="d-flex justify-content-between flex-wrap">
                <p><?php echo $order['order_date']; ?></p>
                <p>requester:_ <?php echo $order['username']; ?></p>
            </div>

            <div class="d-flex justify-content-between flex-wrap">
                <div class="student_name fw-bold mb-3">
                    <?php echo $order['student_name'];?>
                </div>

                <p>paymentMethod:_ <?php echo $payment['paymentMethod'];?></p>
            </div>

            <table class="table table-responsive table-bordered">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th scope="col"></th>
                        <?php foreach ($subject_data[0] as $row) {  ?>
                            <th scope="col">
                                <?php echo $row['subject_name'];?>
                            </th>
                        <?php } ?>
                        <th scope="col">total_degrees</th>
                        <th scope="col">percentage</th>
                    </tr>
                    <tr>
                        <th scope="col">max_degree</th>
                        <?php foreach ($subject_data[0] as $row) {  ?>
                            <th scope="col">
                                <?php echo $row['max_degree']; ?>
                            </th>
                        <?php } ?>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    <tr>
                        <th scope="col">min_degree</th>
                        <?php foreach ($subject_data[0] as $row) {  ?>
                            <th scope="col">
                                <?php $total_max_degree[] = $row['min_degree']; echo $row['min_degree'];?>
                            </th>
                        <?php } ?>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <?php for ($t = 0; $t < count($subject_data[0]); $t++) {  ?>
                        <td>
                            <?php  $total[$t] = $subject_data[0][$t]['degree']; echo $subject_data[0][$t]['degree'];?>
                        </td>
                    <?php } ?>
                    <td><?php echo array_sum($total); ?></td>
                    <td><?php echo floor((array_sum($total_max_degree) / array_sum($total)) * 100) . '%'; ?></td>
                </tr>
                </tbody>
            </table>

            <button
                    data-bs-toggle="modal" data-bs-target="#invoice"
                    class="btn btn-success">show invoice</button>


            <!-- Modal -->
            <div class="modal fade" id="invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <p><?php echo date( "d/m/Y", strtotime($payment['date'])); ?></p>
                                    </div>
                                    <div class="item d-flex justify-content-between">
                                        <p class="lead">name parent:_</p>
                                        <p><?php echo $order['username'];?></p>
                                    </div>
                                    <div class="item d-flex justify-content-between">
                                        <p class="lead">address</p>
                                        <p><?php echo $order['address'];?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="invoice_total d-flex justify-content-between">
                                    <p class="lead">invoice_total</p>
                                    <p class="fw-bold"><?php echo $payment['amount']?></p>
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
        </div>
    </div>

<?php
    require BLP . 'shared/footer.php';
?>