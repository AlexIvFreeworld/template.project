<?
function paymentYooKassaTest($price, $elementID, $description, $returnUrl, $failUrl,$userName,$userPhone) {

    // $vars['userName'] = SBERBANK_USER_NAME;
    // $vars['password'] = SBERBANK_PASSWORD;
     
    /* ID заказа в магазине */
    // $vars['orderNumber'] = $elementID;    
    
    /* Сумма заказа в копейках */
    $vars['amount'] = $price;
     
    /* URL куда клиент вернется в случае успешной оплаты */
    $vars['returnUrl'] = $returnUrl;
        
    /* URL куда клиент вернется в случае ошибки */
    $vars['failUrl'] = $failUrl;
     
    /* Описание заказа, не более 24 символов, запрещены % + \r \n */
    $vars['description'] = $description;
  
    $client = new Client();
	$client->setAuth('483742', 'test_K5m2ig-mbuIMxpTykgKSOzMWvSmI7gdvaN2bzHQOHyw');
	$payment = $client->createPayment(
		array(
			'amount' => array(
				'value' => $vars['amount'],
				'currency' => 'RUB',
			),
			'confirmation' => array(
				'type' => 'redirect',
				'return_url' => $vars['returnUrl'],
			),
			'capture' => true,
			'description' => $vars['description'],
            "receipt" => array(
                "customer" => array(
                    "full_name" => $userName,
                    "phone" => $userPhone,
                ),
                "items" => array(
                    array(
                        "description" => $vars['description'],
                        "quantity" => 1.000,
                        "amount" => array(
                            "value" => $vars['amount'],
                            "currency" => "RUB"
                        ),
                        "vat_code" => 2,
                        "payment_mode" => "full_prepayment",
                        "payment_subject" => "commodity"
                    ),
                )
            )
		),
		uniqid('', true)
	);
	$confirmationUrl = $payment->getConfirmation()->getConfirmationUrl();
    $orderId = $payment->getId();
    \Bitrix\Main\Diag\Debug::dumpToFile(array('$payment' => $payment), "", "/log.txt");
    \Bitrix\Main\Diag\Debug::dumpToFile(array('$confirmationUrl' => $confirmationUrl), "", "/log.txt");
    if (!empty($orderId)) {
        CIBlockElement::SetPropertyValuesEx($elementID, false, array('SBERBANK_ORDER_ID' => $orderId));
        $_SESSION["SBERBANK_ORDER_ID"] = $orderId;
        header("Location: " . $confirmationUrl);
    } else {
        header('Location: ' . $failUrl . (strpos($failUrl, '?') ? '&' : '?') . 'message=' . 'error', true);
    }
}

$client = new YooKassa\Client();
$client->setAuth('483742', 'test_K5m2ig-mbuIMxpTykgKSOzMWvSmI7gdvaN2bzHQOHyw');

$paymentId = '2eb14ef2-000f-5000-b000-18b5357c70bc';
$payment = $client->getPaymentInfo($paymentId);
pp($payment->getStatus());