<?use \Bitrix\Main\Localization\Loc;?>
<?//show order and sale block?>

<?if($templateData['ORDER_BTN']):?>
    <div class="detail-block ordered-block order_sale">
        <div class="rounded-4 bordered grey-bg">
            <?$APPLICATION->ShowViewContent('PRICE_ONLINE_INFO')?>
        </div>
    </div>
<?endif;?>