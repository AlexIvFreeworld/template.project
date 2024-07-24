<?
// R52::debug($arResult["ITEMS"]);
$body = file_get_contents('php://input');
$body = json_decode($body);
// \Bitrix\Main\Diag\Debug::dumpToFile(array('$body' => $body), "", "/log.txt");
// \Bitrix\Main\Diag\Debug::dumpToFile(array('$_POST' => $_POST), "", "/log.txt");

?>
<? if ($body->mode == "ajax") : ?>
    <? $APPLICATION->RestartBuffer(); ?>
    <? if ($body->view == "letters") : ?>
        <div class="letters dual">
            <? foreach ($arResult["ITEMS"] as $key => $arItem) : ?>
                <? if ($start || $first_letter != mb_substr($arItem['NAME'], 0, 1)) : ?>
                    <? if (!$start) : ?>
                        </ul>
        </div>
    <? else : ?>
        <? $start = false ?>
    <? endif ?>
    <div class="letter-block">
        <span class="anchor-block" id="<?= mb_substr($arItem['NAME'], 0, 1) ?>"></span>
        <h3 class="h3"><? echo mb_substr($arItem['NAME'], 0, 1) ?></h3>
        <ul>
        <? endif ?>
        <li><a href="<? echo $arItem['DETAIL_PAGE_URL'] ?>"><? echo $arItem['NAME'] ?></a></li>

        <? $first_letter = mb_substr($arItem['NAME'], 0, 1); ?>
    <? endforeach ?>
        </ul>
    </div>
<? endif ?>
<? if ($body->view == "industries" || $body->view == "materials") : ?>
    <div class="letters dual">
        <span class="anchor-block"></span>
        <div class="letter-block">
            <ul>
                <? foreach ($arResult["ITEMS"] as $key => $arItem) : ?>
                    <li><a href="<? echo $arItem['DETAIL_PAGE_URL'] ?>"><? echo $arItem['NAME'] ?></a></li>
                <? endforeach; ?>
            </ul>
        </div>
    </div>
<? endif; ?>
<? die(); ?>
<? endif; ?>