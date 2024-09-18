<?

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Config\Option;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

global $APPLICATION;
IncludeModuleLangFile(__FILE__);

$moduleID = "ialex.importxml";
\Bitrix\Main\Loader::includeModule($moduleID);
$RIGHT = $APPLICATION->GetGroupRight($moduleID);
$arErr = array();
$body = file_get_contents('php://input');
$body = json_decode($body);
// \Bitrix\Main\Diag\Debug::dumpToFile(array('date("d-m-Y H:i:s")' => date("d-m-Y H:i:s")), "", "/log.txt");
// \Bitrix\Main\Diag\Debug::dumpToFile(array('$body' => $body), "", "log.txt");

if ($RIGHT >= "R" && isset($body->type) && $body->type == "sections") {
    if (CModule::IncludeModule('iblock')) {
        $isBlock = false;
        $resBlocks = CIBlock::GetList(
            array("SORT" => "ASC"),
            array("ID" => $body->iblockId),
            false
        );
        if ($ar_res = $resBlocks->GetNext()) {
            $isBlock = true;
        }
        if (!$isBlock) {
            $arErr[] = "Инфоблока с таким id не существует";
        }
        $arSectionsIds = array();
        if ($isBlock) {
            $arLists = CIBlockSection::GetList(
                array('SORT' => 'ASC'),
                array('IBLOCK_ID' => $body->iblockId),
                false,
                array('NAME', 'CODE', 'ID')
            );
            while ($arList = $arLists->GetNext()) {
                // debug($arList);
                $arSectionsIds[] = $arList["ID"];
            }
            if (empty($arSectionsIds)) {
                $arErr[] = "В инфоблоке нет активных разделов";
            }
        }
    }
} else {
    $arErr[] = "error";
}
?>
<? $APPLICATION->RestartBuffer(); ?>
<? if (empty($arErr)): ?>
    <h3>Сопоставление разделов сайта с внешним ресурсом</h3>
    <table class="ialex-table-xml">
        <tr>
            <th>ИД Раздела инфоблока на сайте</th>
            <th>ИД Рубрики на elec_market</th>
        </tr>
        <? foreach ($arSectionsIds as $key => $sectioId): ?>
            <tr>
                <td>
                    <input type="text" name="sec_in_<?= ($key + 1) ?>" value="<?= $sectioId ?>" class="form__field" required>
                </td>
                <td>
                    <input type="text" name="sec_out_<?= ($key + 1) ?>" class="form__field" required>
                </td>
            </tr>
        <? endforeach; ?>
    </table>
<? else: ?>
    <h3 class="error">Ошибки:</h3>
    <? foreach ($arErr as $err): ?>
        <span><?= $err ?></span><br>
    <? endforeach; ?>
<? endif; ?>
<? die(); ?>