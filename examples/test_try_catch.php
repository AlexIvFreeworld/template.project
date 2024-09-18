<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use AI\R52;

?>
<?

try {
    // код, который может вызвать исключение
    $a = 5;
    $b = 0;
    $result = $a / $b;
    echo $result;
} catch (DivisionByZeroError $ex) {
    // обработка исключения
    echo "Произошло исключение:<br>";
    echo $ex . "<br>";
}
R52::debug("Конец работы программы");

try {
    $path = "/home/host4404/nicp.ru/log.txt";
    // R52::debug("isFileExists :" . \Bitrix\Main\IO\File::isFileExists($path));
    if(!\Bitrix\Main\IO\File::isFileExists($path)){
        throw new \Bitrix\Main\IO\FileNotFoundException($path);
    }
} catch (\Bitrix\Main\IO\FileNotFoundException $ex) {
    echo "Произошло исключение:<br>";
    echo $ex . "<br>";
}
R52::debug("Конец работы программы 2");

try {
    $arr = [1, 3];
    $i = strlen($arr[2]);
} catch (\Bitrix\Main\SystemException $ex) {
    echo "Произошло исключение:<br>";
    echo $ex . "<br>";
}
R52::debug("Конец работы программы 3");

function inverse($x)
{
    if (!$x) {
        throw new Exception('Деление на ноль.');
    }
    return 1 / $x;
}

try {
    echo inverse(5) . "<br>";
    echo inverse(0) . "<br>";
} catch (Exception $e) {
    echo 'PHP перехватил исключение: ',  $e->getMessage(), "<br>";
}

// Продолжить выполнение
R52::debug("Конец работы программы 4");

$arNull = '';
try {
    R52::debug( count($arNull));
} catch (Error $e) {
    echo 'PHP перехватил ошибку: ',  $e->getMessage(), "<br>";
    echo 'код исключения: ',  $e->getCode(), "<br>";
    echo 'название файла, в котором возникла ошибка: ',  $e->getFile(), "<br>";
    echo 'номер строки, в которой возникла ошибка: ',  $e->getLine(), "<br>";
    echo 'трассировка стека: ',  $e->getTrace(), "<br>";
    echo 'трассировка стека в виде строки: ',  $e->getTraceAsString(), "<br>";
}
R52::debug("Конец работы программы 5");

?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>