<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
$body = file_get_contents('php://input');
$body = json_decode($body);
\Bitrix\Main\Diag\Debug::dumpToFile(array('$body' => $body), "", "log.txt");
\Bitrix\Main\Diag\Debug::dumpToFile(array('$_GET' => $_GET), "", "log.txt");

// response html
?>
<? if ($_GET["mode"] == "html") : ?>
    <div id="ajax-form">
        <form action="">
            <input type="text">
            <input type="text">
        </form>
        <button>send</button>
    </div>
<? endif; ?>