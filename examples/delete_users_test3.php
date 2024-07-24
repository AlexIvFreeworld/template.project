<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
?>
<?
$filter = array(
    // "ID"                  => "1 | 2",
    // "TIMESTAMP_1"         => "04.02.2004", // в формате текущего сайта
    // "TIMESTAMP_2"         => "04.02.2005",
    // "LAST_LOGIN_1"        => "01.02.2004",
    "ACTIVE"              => "Y",
    // "LOGIN"               => "nessy | admin",
    // "NAME"                 => "Виталий & Соколов",
    // "EMAIL"               => "mail@server.com | mail@server.com",
    // "KEYWORDS"            => "www.bitrix.ru",
    // "PERSONAL_PROFESSION" => "системотехник",
    // "PERSONAL_GENDER"     => "M",
    // "PERSONAL_COUNTRY"    => "4 | 1", // Беларусь или Россия
    // "ADMIN_NOTES"         => "\"UID = 145\"",
    // "GROUPS_ID"           => array(1, 4, 10)
);
$rsUsers = CUser::GetList(($by = "id"), ($order = "asc"), $filter); // выбираем пользователей
// $is_filtered = $rsUsers->is_filtered; // отфильтрована ли выборка ?
// $rsUsers->NavStart(50); // разбиваем постранично по 50 записей
// echo $rsUsers->NavPrint(GetMessage("PAGES")); // печатаем постраничную навигацию
while ($rsUsers->NavNext(true, "f_")) :
    // debug($rsUsers);
    echo "[" . $f_ID . "] (" . $f_LOGIN . ") " . $f_NAME . " " . $f_LAST_NAME . "<br>";
    if($f_ID > 1){
        if (CUser::Delete($f_ID)) echo "Пользователь " .$f_ID. " удален.";
    }
endwhile;

?>
<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
