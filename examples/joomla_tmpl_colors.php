<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
define("TEMPLATE_PATH_IMG", "/templates/template/images/");
define("TEMPLATE_PATH", "/templates/template/");

?>
<?
$user = &JFactory::getUser();
// $app = JFactory::getApplication();
if (!$user->guest) {
    //   echo 'Вы залогинены как:<br />';
    //   echo 'Username: ' . $user->username . '<br />';
    //   echo 'Настоящее имя: ' . $user->name . '<br />';
    //   echo 'ID пользователя: ' . $user->id . '<br />';
}
$arWindowFunSrc = array();
foreach ($list3 as $arItem) {
    $arImage = json_decode($arItem->images);
    $code = str_replace(" ", "", $arItem->jcfields[18]->value);
    $arWindowFunSrc[$code] = $arImage->image_intro;
}
// echo "<pre>";
// print_r($arWindowFunSrc);
// echo "</pre>";
$jsonData = json_encode($arWindowFunSrc);
?>
<script>
  var data = <?php echo $jsonData; ?>;
//   console.log(data);
</script>

<? if ($user->id == 0) : ?>
    <!-- Custom color -->
    <section class="custom-color">
        <div class="container">
            <h2 class="custom-color__title">Выберите свой цвет окна и фурнитуры</h2>
            <div class="custom-color__inner">
                <img class="colors-set" src="images/custom-color.png" alt="Описание изображения" />
                <form class="custom-color__content">
                    <div class="custom-color__item">
                        <div class="custom-color__type">Выберите цвет окна</div>
                        <div class="custom-color__vars frame">
                            <? foreach ($list as $key => $item) : ?>
                                <? //echo "<pre>";print_r($item);echo "</pre>";
                                ?>
                                <?php require JModuleHelper::getLayoutPath('mod_articles_news', '_item_colors_frame'); ?>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="custom-color__item">
                        <div class="custom-color__type">Выберите цвет фурнитуры</div>
                        <div class="custom-color__vars acessories">
                            <? foreach ($list as $key => $item) : ?>
                                <? //echo "<pre>";print_r($item);echo "</pre>";
                                ?>
                                <?php require JModuleHelper::getLayoutPath('mod_articles_news', '_item_colors_accessories'); ?>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <span class="custom-color__submit callback-toggle-f">Заказать</span>
                </form>
            </div>
        </div>
    </section>
    <!-- END Custom color -->
<? endif ?>