<?php
CModule::AddAutoloadClasses(
    '',
    array(
        'R52' => '/local/classes/r52.php',
    )
);
// AddEventHandler( "sale" , "OnSaleOrderSaved" , "onSaleOrderSaved" ); //Событие при оплате заказа
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderSaved',
    'onSaleOrderSaved'
);

function onSaleOrderSaved(\Bitrix\Main\Event $event)
{
    \Bitrix\Main\Diag\Debug::dumpToFile(array('date' => date("Y-m-d H:i:s")), "", "/log.txt");
    \Bitrix\Main\Diag\Debug::dumpToFile(array('$_REQUEST["ONE_CLICK_BUY"]' => $_REQUEST["ONE_CLICK_BUY"]), "", "/log.txt");

    if (!$event->getParameter("IS_NEW")) {
        $isNew = $event->getParameter("IS_NEW");
        // \Bitrix\Main\Diag\Debug::dumpToFile(array('$isNew' => $isNew), "", "/log.txt");
        return;
    }
    if (!empty($_REQUEST["ONE_CLICK_BUY"])) {
        return;
    }
    $order = $event->getParameter("ENTITY");
    // $oldValues = $event->getParameter("VALUES");
    $propertyCollection = $order->getPropertyCollection();
    $propsData = [];
    foreach ($propertyCollection as $propertyItem) {
        if (!empty($propertyItem->getField("CODE"))) {
            $propsData[$propertyItem->getField("CODE")] = trim($propertyItem->getValue());
        }
    }
    \Bitrix\Main\Diag\Debug::dumpToFile(array('$propsData' => $propsData), "", "/log.txt");
    \Bitrix\Main\Diag\Debug::dumpToFile(array('$order->getDeliveryPrice()' => $order->getDeliveryPrice()), "", "/log.txt");
    // \Bitrix\Main\Diag\Debug::dumpToFile(array('$oldValues' => $oldValues), "", "/log.txt");


    //Тут происходит какая то логика для оплаченного заказа
    //ID заказа: $order->getId()
    //ID пользователя: $order->getUserId()
    //Сумма заказа: $order->getPrice()
    //Размер скидки: $order->getDiscountPrice()
    //Стоимость доставки: $order->getDeliveryPrice()
    //Оплаченная сумма: $order->getSumPaid()
    //Сумма заказа: $order->getPrice()
    $rsUser = CUser::GetByID($order->getUserId());
    $arUser = $rsUser->Fetch();
    // \Bitrix\Main\Diag\Debug::dumpToFile(array('$arUser' => $arUser), "", "/log.txt");
    $basket = $order->getBasket();
    \Bitrix\Main\Diag\Debug::dumpToFile(array('$basket->getListOfFormatText()' => $basket->getListOfFormatText()), "", "/log.txt");

    $arEventFields = array(
        "NAME" => $propsData["FIO"],
        "PHONE" => $propsData["PHONE"],
        "EMAIL" => $propsData["EMAIL"],
        "ADDRESS_DELIVERY" => $propsData["ADDRESS"],
        // "COMPANY" => $propsData["FIO"],
        "ORDER_ID" => $order->getId(),
        "ORDER_LIST" => $basket->getListOfFormatText(),
        "PRICE" => $order->getPrice(),
    );

    CEvent::Send("SALE_NEW_ORDER_ADMIN", SITE_ID, $arEventFields, "N", 62);
}
