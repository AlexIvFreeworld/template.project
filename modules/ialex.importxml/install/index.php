<?php
//подключаем основные классы для работы с модулем
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
//в данном модуле создадим адресную книгу, и здесь мы подключаем класс, который создаст нам эту таблицу
use Module\Adress\AdressTable;

Loc::loadMessages(__FILE__);
//в названии класса пишем название директории нашего модуля, только вместо точки ставим нижнее подчеркивание
class ialex_importxml extends CModule
{
  const partnerName = 'ialex';
  const solutionName  = 'importxml';

  public function __construct()
  {
    $arModuleVersion = array();
    //подключаем версию модуля (файл будет следующим в списке)
    include __DIR__ . '/version.php';
    //присваиваем свойствам класса переменные из нашего файла
    if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
      $this->MODULE_VERSION = $arModuleVersion['VERSION'];
      $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    }
    //пишем название нашего модуля как и директории
    $this->MODULE_ID = 'ialex.importxml';
    // название модуля
    $this->MODULE_NAME = Loc::getMessage('IALEX_IMPORTXML_MODULE_NAME');
    //описание модуля
    $this->MODULE_DESCRIPTION = Loc::getMessage('IALEX_IMPORTXML_MODULE_DESCRIPTION');
    //используем ли индивидуальную схему распределения прав доступа, мы ставим N, так как не используем ее
    $this->MODULE_GROUP_RIGHTS = 'N';
    //название компании партнера предоставляющей модуль
    $this->PARTNER_NAME = Loc::getMessage('IALEX_IMPORTXML_MODULE_PARTNER_NAME');
    $this->PARTNER_URI = 'https://ialex.ru'; //адрес вашего сайта
  }
  //здесь мы описываем все, что делаем до инсталляции модуля, мы добавляем наш модуль в регистр и вызываем метод создания таблицы
  public function doInstall()
  {
    ModuleManager::registerModule($this->MODULE_ID);
    $this->InstallFiles();
    $this->InstallDB();
  }
  //вызываем метод удаления таблицы и удаляем модуль из регистра
  public function doUninstall()
  {
    // $this->uninstallDB();
    ModuleManager::unRegisterModule($this->MODULE_ID);
    $this->UnInstallFiles();
    $this->UnInstallDB(array(
      "savedata" => $_REQUEST["savedata"],
    ));
  }
  //вызываем метод создания таблицы из выше подключенного класса
  // public function installDB()
  // {
  //     if (Loader::includeModule($this->MODULE_ID)) {
  //         AdressTable::getEntity()->createDbTable();

  //     }
  // }
  //вызываем метод удаления таблицы, если она существует
  // public function uninstallDB()
  // {
  //     if (Loader::includeModule($this->MODULE_ID)) {
  //         if (Application::getConnection()->isTableExists(Base::getInstance('\Module\Adress\AdressTable')->getDBTableName())) {
  //             $connection = Application::getInstance()->getConnection();
  //             $connection->dropTable(AdressTable::getTableName());
  //         }
  //     }
  // }
  function InstallDB()
  {
    global $APPLICATION, $DB, $errors;

    if (!$DB->Query("SELECT 'x' FROM ialex_elec_categories", true)) {
      $EMPTY = "Y";
    } else {
      $EMPTY = "N";
    }

    $errors = false;

    if ($EMPTY == "Y") {
      $errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/local/modules/ialex.importxml/install/db/install.sql");
    }

    if (!empty($errors)) {
      $APPLICATION->ThrowException(implode("", $errors));
      return false;
    }

    return true;
  }

  function UnInstallDB($arParams = array())
  {
    global $APPLICATION, $DB, $errors;

    if (!array_key_exists("savedata", $arParams) || $arParams["savedata"] != "Y") {
      $errors = false;
      // delete whole base
      $errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/local/modules/ialex.importxml/install/db/uninstall.sql");

      if (!empty($errors)) {
        $APPLICATION->ThrowException(implode("", $errors));
        return false;
      }
    }

    return true;
  }

  function InstallFiles()
  {
    CopyDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true);
    CopyDirFiles(__DIR__ . '/css/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/css/' . self::partnerName . '.' . self::solutionName, true, true);
    CopyDirFiles(__DIR__ . '/js/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/' . self::partnerName . '.' . self::solutionName, true, true);
    // CopyDirFiles(__DIR__.'/tools/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/tools/'.self::partnerName.'.'.self::solutionName, true, true);
    CopyDirFiles(__DIR__ . '/images/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/images/' . self::partnerName . '.' . self::solutionName, true, true);
    // CopyDirFiles(__DIR__.'/components/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/components', true, true);
    // CopyDirFiles(__DIR__.'/wizards/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards', true, true);

    return true;
  }
  function UnInstallFiles()
  {
    DeleteDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
    DeleteDirFilesEx('/bitrix/css/' . self::partnerName . '.' . self::solutionName . '/');
    DeleteDirFilesEx('/bitrix/js/' . self::partnerName . '.' . self::solutionName . '/');
    // DeleteDirFilesEx('/bitrix/tools/'.self::partnerName.'.'.self::solutionName.'/');
    DeleteDirFilesEx('/bitrix/images/' . self::partnerName . '.' . self::solutionName . '/');
    // DeleteDirFilesEx('/bitrix/wizards/'.self::partnerName.'/'.self::solutionName.'/');

    return true;
  }
}
