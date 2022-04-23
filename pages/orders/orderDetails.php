<?php
    require '../../config.php';
    require BLP . 'shared/header.php';

    $order = NULL;
    $subject_data = [];
    $total = [];
    $total_max_degree = [];
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $orderId = intval($_GET['id']);
        $order = getRow("
            SELECT _order.id, _order.order_date, _order.invoiceId, _order.parentId, _order.resultId, result.student_name, parent.username FROM `_order` 
            LEFT JOIN `result` ON result.id = _order.resultId 
            LEFT JOIN `parent` ON parent.id = _order.parentId 
            WHERE _order.id = $orderId");

        $student_name = $order['student_name'];
        $resultSql = "SELECT `subject_name`, `degree`, `max_degree`, `min_degree`, result.id FROM `result` LEFT JOIN subject ON result.subjectId = subject.id WHERE `student_name` = '$student_name'";
        $subject_data[] = getResults($resultSql);
    } else {
        header('location:' . BASEURLPAGES . 'orders/viewParentOrder.php');
    }
?>

    <div class="main" id="main">
        <div class="content">
            <div class="d-flex justify-content-between flex-wrap">
                <p><?php echo $order['order_date']; ?></p>
                <p>requester:_ <?php echo $order['username']; ?></p>
            </div>

            <div class="student_name fw-bold mb-3">
                <?php echo $order['student_name'];?>
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

            <button class="btn btn-success">show invoice</button>
        </div>
    </div>

<?php
    require BLP . 'shared/footer.php';
?>