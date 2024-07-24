<?
if (isset($arResult["CANONICAL_LINK"])) {
    $APPLICATION->SetPageProperty("canonical", $arResult["CANONICAL_LINK"]);
}
CJSCore::Init();
?>
<? if ($_GET["TYPE"] == "REPORT_RESULT") : ?>
    <? if (isset($_GET["ID"])) : ?>
        <script>
            var textElem = document.getElementById("ajax-report-text");
            textElem.innerText = "Ваше мнение учтено №" + "<?= $_GET["ID"] ?>";
            window.history.pushState(null, null, "<?= $APPLICATION->GetCurPage() ?>");
        </script>
    <? else : ?>
        <script>
            var textElem = document.getElementById("ajax-report-text");
            textElem.innerText = "Ошибка";
            window.history.pushState(null, null, "<?= $APPLICATION->GetCurPage() ?>");
        </script>
    <? endif; ?>
<? elseif (isset($_GET["ID"])) : ?>
    <?
    $jasonObject = array();
    if (CModule::IncludeModule("iblock")) {
        $arUser = "";
        global $USER;
        if ($USER->IsAuthorized()) {
            $arUser = $USER->GetId() . "(" . $USER->GetLogin() . ")" . $USER->GetFullName();
        } else {
            $arUser = "Не авторизован";
        }
        $arField = array(
            "IBLOCK_ID" => 6,
            "NAME" => "Новость " . $_GET["ID"],
            "ACTIVE_FROM" => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
            "PROPERTY_VALUES" => array(
                "USER" => $arUser,
                "NEWS" => $_GET["ID"]
            )
        );
        $element = new CIBlockElement(false);
        if ($elId = $element->add($arField)) {
            $jasonObject["ID"] = $elId;
            if ($_GET["TYPE"] == "REPORT_AJAX") {
                $APPLICATION->RestartBuffer();
                echo json_encode($jasonObject);
                die();
            } elseif ($_GET["TYPE"] == "REPORT_GET") {
                LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT&ID=" . $jasonObject["ID"]);
            }
        } else {
            LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT");
        }
    }

    ?>
<? endif; ?>