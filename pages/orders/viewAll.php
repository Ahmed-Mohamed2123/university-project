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
        <div style="overflow-y: auto">
            <table class="table table-hover" style="cursor: pointer">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <?php if ($role === 0) { ?>
                        <th scope="col">حاله الطلب</th>
                    <?php } ?>
                    <th scope="col">تاريخ الطلب</th>
                    <th scope="col">السعر</th>
                    <th scope="col">الاسم</th>
                    <?php if ($role === 0) { ?>
                        <th scope="col">الملاحظات</th>
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
                                echo 'من فضلك انتظر <img src=' . ASSETS . 'images/clock.png' . ' />';
                            } elseif (intval($row['order_status']) === 1) {
                                echo 'قبلت <img style="width:26px;height:26px" src=' . ASSETS . 'images/accepted.png' . ' />';
                            } elseif (intval($row['order_status']) === 2) {
                                echo 'رفضت <img style="width:26px;height:26px" src=' . ASSETS . 'images/rejected.png' . ' />';
                            } elseif (intval($row['order_status']) === 3) {
                                echo 'قبلت <img style="width:26px;height:26px" src=' . ASSETS . 'images/accepted.png' . ' />';
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
                                echo 'الطلب ربما سيأخذ بضع ساعات من فضلك انتظر';
                            } elseif (intval($row['order_status']) === 1) {
                                echo 'من فضلك اكمل باقى الخطوات لتأخذ طلبك';
                            } elseif (intval($row['order_status']) === 2) {
                                echo 'راجع الاداره لتعرف سبب الرفض';
                            } elseif (intval($row['order_status']) === 3) {
                                echo 'كل العمليات للطلب نجحت , واصبح طلبك متاح لك الآن';
                            }
                            ?>
                        </td>
                    <?php } ?>
                        <td>
                            <!--  for parent  -->
                            <?php if ($role === 0 && intval($row['order_status']) === 0) { ?>
                                <a class="btn btn-danger" href="<?php echo BASEURLPAGES . 'orders/delete.php?id=' . $row['id'];?>">احذف</a>
                            <?php } ?>
                            <?php if ($role === 0 && intval($row['order_status']) === 1) { ?>
                                <a class="btn btn-primary" href="<?php echo BASEURLPAGES . 'orders/checkout.php?id=' . $row['id'] . '&price=' . $row['price'];?>">ادفع ثمن طلبك</a>
                            <?php } ?>
                            <?php if ($role === 0 && intval($row['order_status']) === 3) { ?>
                                <a class="btn btn-primary" href="<?php echo BASEURLPAGES . 'orders/orderDetails.php?id=' . $row['id'];?>">افتح صفحه المطلوب</a>
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
                                            href="<?php echo BASEURLPAGES . 'orders/changeStatusOrder.php?request=accept&id=' . $row['id'];?>">موافقه</a>
                                    <a
                                            class="btn btn-danger"
                                        <?php if (intval($row['order_status']) === 2) {echo 'disabled="disabled"';} ?>
                                            href="<?php echo BASEURLPAGES . 'orders/changeStatusOrder.php?request=reject&id=' . $row['id'];?>">رفض</a>

                                    <a
                                            class="btn btn-success"
                                            href="<?php echo BASEURLPAGES . 'orders/orderDetails.php?id=' . $row['id'];?>">اظهار الطلب</a>
                                <?php } ?>
                            <?php }?>
                        </td>
                    <?php $x++; } ?>
                </tbody>
            </table>
        </div>

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