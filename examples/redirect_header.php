<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\FileHelper;
use intec\constructor\Module as Constructor;
use intec\constructor\models\build\Template;

Loc::loadMessages(__FILE__);

require(__DIR__.'/parts/preload.php');

$request = Core::$app->request;
$page->execute(['state' => 'loading']);

/** @var Template $template */
$template = $build->getTemplate();

if (empty($template))
    return;

foreach ($template->getPropertiesValues() as $key => $value)
    $properties->set($key, $value);

unset($value);
unset($key);

if (!Constructor::isLite())
    $template->populateRelation('build', $build);

if (FileHelper::isFile($directory.'/parts/custom/initialize.php'))
    include($directory.'/parts/custom/initialize.php');

require($directory.'/parts/metrika.php');
require($directory.'/parts/assets.php');

if (FileHelper::isFile($directory.'/parts/custom/start.php'))
    include($directory.'/parts/custom/start.php');

$APPLICATION->AddBufferContent([
    'intec\\template\\Marking',
    'openGraph'
]);

$page->execute(['state' => 'loaded']);
$part = Constructor::isLite() ? 'lite' : 'base';
$APPLICATION->SetPageProperty('canonical', 'https://kolrus.ru'.$APPLICATION->GetCurPage(false));
if(strpos($APPLICATION->GetCurPage(),"№_19_17_Zakluchenie_specialista_(recenziya_na_zakluchenie_eksperta).pdf") !== false){
header("Location:/services/retsenzirovanie_sudebnykh_ekspertiz/302/");
}
if(strpos($APPLICATION->GetCurPage(),"№_10_17_Zakluchenie_specialista__(pocherkovedcheskoe_issledovanie)") !== false){
header("Location:/services/pocherkovedcheskaya_ekspertiza_issledovanie/298/");
}
?><!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
    <head>
        <?php if (FileHelper::isFile($directory.'/parts/custom/header.start.php')) include($directory.'/parts/custom/header.start.php') ?>
        <title><?php $APPLICATION->ShowTitle() ?></title>
        <?php $APPLICATION->ShowHead() ?>
        <meta name="viewport" content="initial-scale=1.0, width=device-width">
        <meta name="cmsmagazine" content="79468b886bf88b23144291bf1d99aa1c" />
        <?php $APPLICATION->ShowMeta('og:type', 'og:type') ?>
        <?php $APPLICATION->ShowMeta('og:title', 'og:title') ?>
        <?php $APPLICATION->ShowMeta('og:description', 'og:description') ?>
        <?php $APPLICATION->ShowMeta('og:image', 'og:image') ?>
        <?php $APPLICATION->ShowMeta('og:url', 'og:url') ?>
        <link rel="canonical" href="<?=$APPLICATION->GetProperty("canonical")?>" />
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
        <link rel="apple-touch-icon" href="/favicon.png">
        <?php if (!Constructor::isLite()) { ?>
            <style type="text/css"><?= $template->getCss() ?></style>
            <style type="text/css"><?= $template->getLess() ?></style>
            <script type="text/javascript"><?= $template->getJs() ?></script>
        <?php } ?>
        <?php if (FileHelper::isFile($directory.'/parts/custom/header.end.php')) include($directory.'/parts/custom/header.end.php') ?>
    </head>
    <style>
        .c-header.c-header-template-1.widget-transparent[data-color=white] .widget-view.widget-view-desktop .widget-container-logotype svg{
            fill: unset !important;
        }
    </style>
   <?$IS_MAIN = CSite::InDir(SITE_DIR . 'index.php') ? true : false;?>
    <body class="public intec-adaptive <?=($IS_MAIN ? 'main_wrapper' : '')?>">
        <?php if (FileHelper::isFile($directory.'/parts/custom/body.start.php')) include($directory.'/parts/custom/body.start.php') ?>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:system',
            'basket.manager',
            array(
                'BASKET' => 'Y',
                'COMPARE' => 'Y',
                'COMPARE_NAME' => 'compare',
                'CACHE_TYPE' => 'N'
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        ); ?>
        <?php if (
            $properties->get('base-settings-show') == 'all' ||
            $properties->get('base-settings-show') == 'admin' && $USER->IsAdmin()
        ) { ?>
            <?php $APPLICATION->IncludeComponent(
                'intec.universe:system.settings',
                '.default',
                array(
                    'MODE' => 'render',
                    'MENU_ROOT_TYPE' => 'top',
                    'MENU_CHILD_TYPE' => 'left'
                ),
                false,
                array(
                    'HIDE_ICONS' => 'N'
                )
            ); ?>
        <? } ?>
        <?php include($directory.'/parts/'.$part.'/header.php'); ?>