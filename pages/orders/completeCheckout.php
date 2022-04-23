<?php
    require '../../config.php';
    require BLP . 'shared/header.php';
    require_once BL . '/vendor/autoload.php';
    use Omnipay\Omnipay;

if(
    isset($_GET['id']) &&
    is_numeric($_GET['id']) &&
    isset($_GET['price'])
) {
    $price = intval($_GET['price']);
    $orderId = intval($_GET['id']);
    $profileId = intval($_SESSION['id']);
    if (isset($_POST['stripeToken'])) {
        if (!empty($_POST['stripeToken'])) {
            try {
                $gateway = Omnipay::create('Stripe');
                $gateway->setApiKey('sk_test_51K2rCpLJlQnKOvm2DdZvmdtZAmy0N7t8QEJEg8wqsrYRXjNVArzqLWO2RsxOVi67azfuQKWl8bq1mH1HRaY3cJnp00BMM4Vlk1');
                $token = $_POST['stripeToken'];

                $response = $gateway->purchase([
                    'amount' => $price,
                    'currency' => 'USD',
                    'token' => $token,
                ])->send();

                if ($response->isSuccessful()) {
                    // payment was successful: update database
                    $arr_payment_data = $response->getData();
                    $payment_id = $arr_payment_data['id'];
                    $amount = $_POST['amount'];
                    foreach ($response->getData() as $_data) {
                        if (!empty($_data['brand'])) {
                            $paymentBrand = $_data['brand'];
                            $result_invoice = db_insert("INSERT INTO `invoice` (`invoice_total`, `parentId`) VALUES ($price, $profileId)");
                            if ($result_invoice['boolean'] === true) {
                                $invoiceId = $result_invoice['id'];


                                $result_payment = db_insert("INSERT INTO `payment` 
                                        (`amount`, `paymentMethod`, `invoiceId`, `parentId`) 
                                        VALUES ($price, '$paymentBrand', $invoiceId, $profileId)");

                                $result_update_order = db_update("UPDATE `_order` SET `invoiceId`=$invoiceId WHERE `id`= $orderId ");


                                if ($result_payment['boolean'] === true &&
                                    $result_update_order['boolean'] === true) {
                                    $success_message = 'The process was completed successfully. After 3 minutes, you will be directed to the orders page.';
                                } else {
                                    $error_message = 'An error has occurred';
                                }
                            }
                        }
                    }

                } else {
                    // payment failed: display message to customer
                    echo $response->getMessage();
                }
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        }
    } elseif (isset($_POST['submitCash'])) {
        $result_invoice = db_insert("INSERT INTO `invoice` (`invoice_total`, `parentId`) VALUES ($price, $profileId)");
        if ($result_invoice['boolean'] === true) {
            $invoiceId = $result_invoice['id'];


            $result_payment = db_insert("INSERT INTO `payment` 
                                        (`amount`, `paymentMethod`, `invoiceId`, `parentId`) 
                                        VALUES ($price, 'cash', $invoiceId, $profileId)");

            $result_update_order = db_update("UPDATE `_order` SET `invoiceId`=$invoiceId WHERE `id`= $orderId ");


            if ($result_payment['boolean'] === true &&
                $result_update_order['boolean'] === true) {
                $success_message = 'The process was completed successfully. After 3 minutes, you will be directed to the orders page.';
            } else {
                $error_message = 'An error has occurred';
            }
        }
    }

    require BL . 'utils/error.php';

} else {
    header('location:' . BLP . 'index.php');
}

require BLP . 'shared/footer.php';