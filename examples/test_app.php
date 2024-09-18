<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?

use AI\R52;
use Bitrix\Main\Application;

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

$value = $request->get("param");       // получение параметра GET или POST
$value = $request["param"];            // получение параметра GET или POST
$value = $request->getQuery("_ym_debug");  // получение GET-параметра
R52::debug($value);
$values = $request->getQueryList();    // получение списка GET-параметров
R52::debug($values);
$value = $request->getPost("param");   // получение POST-параметра
$values = $request->getPostList();     // получение списка POST-параметров
R52::debug($values);
$value = $request->getFile("param");   // получение загруженного файла
$values = $request->getFileList();     // получение списка загруженных файлов
$value = $request->getCookie("param"); // получение значения кука
$values = $request->getCookieList();   // получение списка кукисов

$method = $request->getRequestMethod(); // получение метода запроса
R52::debug($method);
R52::debug($_REQUEST);
// $flag = $request->isGet();              // true - GET-запрос, иначе false
$flag = $request->isPost();             // true - POST-запрос, иначе false
$flag = $request->isAjaxRequest();      // true - AJAX-запрос, иначе false
$flag = $request->isHttps();            // true - HTTPS-запрос, иначе false

$flag = $request->isAdminSection();            // true - находимся в админке, иначе false
$requestUri = $request->getRequestUri();       // Запрошенный адрес (напр. "/catalog/category/?param=value")
$requestPage = $request->getRequestedPage();   // Запрошенная страница (напр. "/catalog/category/index.php")
$rDir  = $request->getRequestedPageDirectory();// Директория запрошенной страницы (напр. "/catalog/category")
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>