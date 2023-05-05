<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js" async="async" charset="utf-8"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,viber,whatsapp,telegram"></div>


<div class="col-md-6">
    <div class="ya-share">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR . "include/ya-share.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        ); ?>
    </div>
</div>