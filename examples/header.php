<html lang="<?=LANGUAGE_ID?>">

<head>
    <? $APPLICATION->ShowHead(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">
    <meta name="format-detection" content="email=no">
    <?php

    use Bitrix\Main\Page\Asset;

    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/style.css");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/build.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/custom.js");
    ?>
</head>

<body class="page__inner">
    <?php $APPLICATION->ShowPanel(); ?>
   
</body>    