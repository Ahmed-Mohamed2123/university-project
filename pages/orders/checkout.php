<?php
    require '../../config.php';
    require BLP . 'shared/header.php';

    $price = NULL;
    $orderId = NULL;
    $paymentMethod = NULL;

    if(
            isset($_GET['id']) &&
            is_numeric($_GET['id']) &&
            isset($_GET['price'])
    ) {
        $price = intval($_GET['price']);
        $orderId = intval($_GET['id']);
        $profileId = intval($_SESSION['id']);


        if ($_POST['paymentMethod'] === 'card') {
            $paymentMethod = 'card';
        } else {
            $paymentMethod = 'cash';
        }


        $_SESSION['paymentMethod'] = $paymentMethod;


    }


?>

<div class="main checkout" id="main">
    <form action="<?php echo BASEURLPAGES . 'orders/checkout.php?id=' . $orderId . '&price=' . $price;?>" method="post">
        <p class="lead mb-3">Please choose a payment method that suits you.</p>
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="form-check">
                <input
                        class="form-check-input"
                        value="cash"
                        type="radio"
                        <?php if ($_SESSION['paymentMethod'] == 'cash') {echo 'checked';}?>
                        name="paymentMethod" id="flexRadioDefault1">
                <label class="form-check-label" for="flexRadioDefault1">
                    cash
                </label>
            </div>
            <div class="form-check">
                <input
                        class="form-check-input"
                        value="card"
                        type="radio"
                        <?php if ($_SESSION['paymentMethod'] == 'card') {echo 'checked';}?>
                        name="paymentMethod" id="flexRadioDefault2">
                <label class="form-check-label" for="flexRadioDefault2">
                    card
                </label>
            </div>

            <button class="btn btn-success">choose</button>
        </div>


    </form>

    <?php if ($paymentMethod === 'cash') { ?>
        <form action="<?php echo BASEURLPAGES . 'orders/completeCheckout.php?id=' . $orderId . '&price=' . $price?>" method="post">
            <button class="btn btn-danger" type="submit" name="submitCash">buy</button>
        </form>
    <?php } ?>

    <?php if ($paymentMethod === 'card') { ?>
        <div class="pagesForm p-3">

            <form action="<?php echo BASEURLPAGES . 'orders/completeCheckout.php?id=' . $orderId . '&price=' . $price?>" method="post" id="payment-form">


                <div class="form-row">
                    <div id="card-element">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>

                    <!-- Used to display form errors. -->
                    <div id="card-errors" class="text-white" role="alert"></div>
                </div>
                <button class="btn btn-danger mt-2" type="submit" name="submitCard">Submit Payment</button>
            </form>
        </div>
    <?php } ?>
</div>

<?php
    require BLP . 'shared/footer.php';
?>