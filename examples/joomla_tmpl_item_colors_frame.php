<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?
$arImage = json_decode($item->images);
// echo "<pre>";
// print_r($arImage);
// echo "</pre>";
$checked = ($key == 0)?"checked":"";
?>

<input type="radio" name="color-frame" id="color-frame-<?=($key + 1)?>" value="ca-<?=($key + 1)?>" hidden <?=$checked?> />
<label for="color-frame-<?=($key + 1)?>" class="custom-color__var frame" data-selected="<?=($key == 0)?'Y':"N" ?>">
    <div class="custom-color__circle">
        <img src="<?= $arImage->image_intro ?>" alt="<?= $item->title ?>" />
    </div>
    <div class="custom-color__name"><?= $item->title ?></div>
</label>