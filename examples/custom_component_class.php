<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class registrationEvent extends CBitrixComponent
{
    private $_request;

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules()
    {
        if (
            !Loader::includeModule('iblock')
            || !Loader::includeModule('sale')
        ) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }

        return true;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllMain|CMain
     */
    private function _app()
    {
        global $APPLICATION;
        return $APPLICATION;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllUser|CUser
     */
    private function _user()
    {
        global $USER;
        return $USER;
    }

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {
        if ($arParams['SERVICES_IBLOCK_ID']) $arParams['SERVICES_IBLOCK_ID'] = (int) $arParams['SERVICES_IBLOCK_ID'];
        if ($arParams['MEMBERS_IBLOCK_ID']) $arParams['MEMBERS_IBLOCK_ID'] = (int) $arParams['MEMBERS_IBLOCK_ID'];
        if ($arParams['ELEMENT_ID']) $arParams['ELEMENT_ID'] = (int) $arParams['ELEMENT_ID'];
        if ($arParams['ELEMENT_CODE']) $arParams['ELEMENT_CODE'] = trim($arParams['ELEMENT_CODE']);
        if ($arParams['PAYMENT_STATUS']) $arParams['PAYMENT_STATUS'] = trim($arParams['PAYMENT_STATUS']);
        if ($arParams['SBERBANK_ORDER_ID']) $arParams['SBERBANK_ORDER_ID'] = trim($arParams['SBERBANK_ORDER_ID']);
        return $arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам 
     */
    public function executeComponent()
    {
        $this->_checkModules();

        $this->_request = Application::getInstance()->getContext()->getRequest();

        global $USER;
        global $DB;
        // валюта по умолчанию
        $defaultCurrency = COption::GetOptionString("sale", "default_currency");
        $this->arResult['RESULT_ERROR'] = array();
        define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . "/log.txt");

        // соревнование
        $arElementFilter = array();
        if ($this->arParams['ELEMENT_ID']) {
            $arElementFilter = array('ID' => $this->arParams['ELEMENT_ID'], 'ACTIVE' => 'Y');
        } elseif ($this->arParams['ELEMENT_CODE']) {
            $arElementFilter = array('CODE' => $this->arParams['ELEMENT_CODE'], 'ACTIVE' => 'Y');
        }

        // $arElementFilter['PERMISSIONS_BY'] = $USER->GetID();
        //$arElementFilter['CHECK_PERMISSIONS'] = "Y";

        // $arElementFilter = [...$arElementFilter, 'PERMISSIONS_BY' => $editUserID, "CHECK_PERMISSIONS" => "Y"];

        if ($arElementFilter) {

            $arSelect = array(
                "ID",
                "NAME",
                "PROPERTY_INDIVIDUAL_CONTRIBUTION",
                "PROPERTY_INDIVIDUAL_CONTRIBUTION_UP_TO_18",
                "PROPERTY_STATUS_REGISTRATION",
                "PROPERTY_STATUS",
                "PROPERTY_SERVICES",
                "CODE",
                "PROPERTY_CATEGORIES",
                "PROPERTY_STAGES",
                "PROPERTY_TYPE_2",
                "IBLOCK_ID"
            );

            $rsElement = CIBlockElement::GetList(array(), $arElementFilter, false, false, $arSelect);
            $arElement = array();

            while ($ob = $rsElement->GetNextElement()) { //pp($arElementResult);

                $arElementResult = $ob->GetFields();
                $arProps = $ob->GetProperties();
                //pp($arProps);
                $arElementResult['PROPERTY_INDIVIDUAL_CONTRIBUTION_VALUE'] = $arProps['INDIVIDUAL_CONTRIBUTION']['VALUE'];
                $arElementResult['PROPERTY_INDIVIDUAL_CONTRIBUTION_UP_TO_18_VALUE'] = $arProps['INDIVIDUAL_CONTRIBUTION_UP_TO_18']['VALUE'];
                $arElementResult['PROPERTY_STATUS_REGISTRATION_VALUE'] = $arProps['STATUS_REGISTRATION']['VALUE'];
                $arElementResult['PROPERTY_STATUS_VALUE'] = $arProps['STATUS']['VALUE'];
                $arElementResult['PROPERTY_SERVICES_VALUE'] = $arProps['SERVICES']['VALUE'];
                $arElementResult['PROPERTY_VALUES'] = $arProps;

                if (!$arElement) {
                    $arElement = $arElementResult;
                    $arElement['CATEGORIES'] = array();
                    $arElement['STAGES'] = array();
                    $arElement['EVENT_TYPES'] = array();
                }

                if ($arProps['CATEGORIES']['VALUE']) {
                    $arElement['CATEGORIES'] = $arProps['CATEGORIES']['VALUE'];
                }

                if ($arProps['STAGES']['VALUE']) {
                    foreach ($arProps['STAGES']['VALUE'] as $key => $val) {
                        $arElement['STAGES'][$val] = $val;
                        $arElement['STAGES_DESCRIPTION'][$val] = unserialize($arProps['STAGES']['~DESCRIPTION'][$key]);
                    }
                }

                if ($arProps['TYPE_2']['VALUE_ENUM_ID']) {
                    $arElement['EVENT_TYPE'] = $arProps['TYPE_2']['VALUE_ENUM_ID'];
                }


                /*if($arElementResult['PROPERTY_CATEGORIES_VALUE'] && !in_array($arElementResult['PROPERTY_CATEGORIES_VALUE'], $arElement['CATEGORIES'])) $arElement['CATEGORIES'][] = $arElementResult['PROPERTY_CATEGORIES_VALUE'];

                if($arElementResult['PROPERTY_STAGES_VALUE'] && !in_array($arElementResult['PROPERTY_STAGES_VALUE'], $arElement['STAGES'])) $arElement['STAGES'][$arElementResult['PROPERTY_STAGES_VALUE']] = $arElementResult['PROPERTY_STAGES_VALUE'];

                if($arElementResult['PROPERTY_TYPE_2_VALUE'] && !in_array($arElementResult['PROPERTY_TYPE_2_VALUE'], $arElement['EVENT_TYPES'])) $arElement['EVENT_TYPES'][$arElementResult['PROPERTY_TYPE_2_ENUM_ID']] = $arElementResult['PROPERTY_TYPE_2_VALUE'];*/

                // этапы
                if ($arElement['STAGES']) {
                    $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_ICON", "PROPERTY_ICON_CODE");
                    $res = CIBlockElement::GetList(array(), array("ID" => $arElement['STAGES']), false, false, $arSelect);
                    while ($arStage = $res->GetNext()) {

                        /*if($arStage['PROPERTY_ICON_VALUE']) {
                            $arStage['PROPERTY_ICON_CODE'] = str_replace('fill="white"', 'fill="#2695FF"', file_get_contents(SITE_URL . CFile::GetPath($arStage['PROPERTY_ICON_VALUE'])));
                        }*/

                        if ($arStage['PROPERTY_ICON_CODE_VALUE']) {
                            $arStage['PROPERTY_ICON_CODE'] = str_replace('fill="white"', 'fill="#2695FF"', htmlspecialchars_decode($arStage['PROPERTY_ICON_CODE_VALUE']));
                        }

                        $arStage['DESCRIPTION'] = $arElement['STAGES_DESCRIPTION'][$arStage['ID']];
                        $arElement['STAGES'][$arStage['ID']] = $arStage;
                    }
                }
            }
            ksort($arElement['EVENT_TYPES']);
            $this->arResult['ELEMENT'] = $arElement;

            $eventUrl = '/registration/' . $this->arResult['ELEMENT']['CODE'] . '/';
            $returnUrl = SITE_URL . $eventUrl . 'success/';
            $failUrl = SITE_URL . $eventUrl . 'error/';
            if (CSite::InDir('/testreg/estafeta-po-plavaniyu-strelka-swim/')) {
                $arElementResult['PROPERTY_STATUS_REGISTRATION_VALUE'] = "Открыта";
                $arElement['PROPERTY_STATUS_REGISTRATION_VALUE'] = "Открыта";
                $eventUrl = '/testreg/' . $this->arResult['ELEMENT']['CODE'] . '/';
            }
            // pp($arElementResult['PROPERTY_STATUS_REGISTRATION_VALUE']);
        }

        if ($arElement['PROPERTY_STATUS_REGISTRATION_VALUE'] != 'Открыта') {
            $this->arResult['RESULT_ERROR']['STATUS_REGISTRATION'] = 'N';
        }
        if ($arElement['PROPERTY_STATUS_VALUE'] != 'Предстоящее')
            $this->arResult['RESULT_ERROR']['STATUS'] = 'N';
        //pp($this->arResult['RESULT_ERROR']); 
        if (!$this->arResult['RESULT_ERROR']) {
            if (($USER->IsAuthorized() && $this->arResult['ELEMENT']) || $this->arParams['PAYMENT_STATUS']) {

                $this->arResult['SESSID'] = bitrix_sessid();
                $this->arResult['USER_ID'] = $USER->GetID();

                $arParameters = array('SELECT' => array('UF_AGE'), 'FIELDS' => array('ID', 'PERSONAL_GENDER', 'PERSONAL_BIRTHDAY'));
                $arUser = CUser::GetList(($by = "name"), ($order = "asc"), array('ID' => $this->arResult['USER_ID']), $arParameters)->fetch();

                // по дате рождения вычисляем возраст
                $arUser['UF_AGE'] = getUserAge($arUser['PERSONAL_BIRTHDAY']);

                // стоимость участия (индивидуальный взнос)
                $this->arResult['ELEMENT']['PRICE'] = $this->arResult['ELEMENT']['PROPERTY_INDIVIDUAL_CONTRIBUTION_VALUE'];

                if ($arUser['UF_AGE'] < 18 && $this->arResult['ELEMENT']['PROPERTY_INDIVIDUAL_CONTRIBUTION_UP_TO_18_VALUE']) {
                    $this->arResult['ELEMENT']['PRICE'] = $this->arResult['ELEMENT']['PROPERTY_INDIVIDUAL_CONTRIBUTION_UP_TO_18_VALUE'];
                }

                // платежные системы
                $this->arResult['PAYMENTS'] = array();
                $rsPayments = CSalePaySystem::GetList(array("SORT" => "ASC", "PSA_NAME" => "ASC"), array("ACTIVE" => "Y"/*, "@ID" => '3,1'*/));
                while ($arPayments = $rsPayments->GetNext()) {
                    $this->arResult['PAYMENTS'][$arPayments['ID']] = $arPayments;
                }


                if (!$this->arParams['PAYMENT_STATUS']) {


                    // проверяем зарегистрирован ли участник на мероприятие ранее
                    $arFilter = array("IBLOCK_ID" => $this->arParams['MEMBERS_IBLOCK_ID'], "PROPERTY_USER" => $this->arResult['USER_ID'], "PROPERTY_EVENT" => $this->arResult['ELEMENT']['ID'], "ACTIVE" => "Y");

                    if (CIBlockElement::GetList(array(), $arFilter, false, false, array('ID'))->SelectedRowsCount() == 0) {

                        $this->arResult['REGISTER_ALREADY'] = "N";

                        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
                        $data = $request->getPostList()->toArray();

                        if ($USER->IsAdmin() && $data["testreg"] == "Y") {
                            // pp($data);
                            // die;
                        }

                        // регистрируем на соревнование
                        //pp($data['ELEMENT_ID'],"ELEMENT_ID");
                        if ($data['ELEMENT_ID'] && $this->arResult['USER_ID'] == $data['USER_ID'] && $this->arResult['SESSID'] == $data['SESSID']) {
                            //pp("is-reg");
                            if ($arElement['EVENT_TYPE'] == EVENT_TYPE_RELAY_RACE) {
                                $data['COMMAND_NAME'] = trim($data['COMMAND_NAME']);
                                if (!$data['COMMAND_NAME']) $this->arResult['RESULT_ERROR']['COMMAND'] = 'Y';
                            }

                            // пересчитываем стоимость, если применен купон
                            $coupon = '';
                            if ($data['COUPON'] && $data['PROMOCODE_YES'] == 'Y') {
                                // применяем купон
                                $arResultCoupon = UseCoupon($data['COUPON'], $this->arResult['USER_ID'], $data['TOTAL_PRICE'], true);
                                if ($arResultCoupon['price'] || $arResultCoupon['price'] == '0') {
                                    $data['TOTAL_PRICE'] = $arResultCoupon['price'];
                                    $coupon = $data['COUPON'];
                                }
                            }

                            $this->arResult['SEND_DATA'] = $data;

                            // проверяем открыта ли регистрация на мероприятие
                            $arElement = CIBlockElement::GetList(array(), array("ID" => $data['ELEMENT_ID']), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_STATUS_REGISTRATION', 'PROPERTY_STATUS'))->GetNext();
                            if (CSite::InDir('/testreg/estafeta-po-plavaniyu-strelka-swim/')) {
                                $arElement['PROPERTY_STATUS_REGISTRATION_VALUE'] = "Открыта";
                                $arElement['PROPERTY_STATUS_VALUE'] = 'Предстоящее';
                            }
                            //pp($arElement['PROPERTY_STATUS_VALUE'],"\$arElement['PROPERTY_STATUS_VALUE']"); 
                            if ($arElement['PROPERTY_STATUS_REGISTRATION_VALUE'] == 'Открыта' && $arElement['PROPERTY_STATUS_VALUE'] == 'Предстоящее') {
                                //pp("is-open");    
                                // если пользователь выбрал оплату с баланса, то проверяем достаточно ли там средств
                                $canBuy = true;
                                if ($data['PAYMENT'] == 1 && $data['TOTAL_PRICE'] > 0) {
                                    Loader::includeModule('sale');
                                    // получаем данные счета пользователя, чтобы понимать, может ли он оплатить с него
                                    $arSaleUser = CSaleUserAccount::GetByUserID($this->arResult['USER_ID'], $defaultCurrency);

                                    if ($arSaleUser['CURRENT_BUDGET'] - $data['TOTAL_PRICE'] < 0) {
                                        $canBuy = false;
                                        $this->arResult['RESULT_ERROR']['USER_BALANCE'] = 'Y';
                                    }
                                }
                                //pp($canBuy,"\$canBuy");
                                if ($canBuy) {
                                    // способ оплаты 
                                    $data['PAYMENT'] = $data['PAYMENT'] ?: 1;

                                    $arProperties = array(
                                        "USER" => $this->arResult['USER_ID'],
                                        "EVENT" => $data['ELEMENT_ID'],
                                        "PRICE" => $data['PRICE'],
                                        "SERVICES_PRICE" => $data['SERVICES_PRICE'],
                                        "TOTAL_PRICE" => $data['TOTAL_PRICE'],
                                        "SERVICES" => $data['SERVICES'],
                                        "DISTANCE" => $data['DISTANCE'],
                                        //"PAYMENT_STATUS" => PEGISTER_PAYMENT_STATUS_N,
                                        //"PAYMENT_METHOD" => $this->arResult['PAYMENTS'][$data['PAYMENT']]['NAME'],
                                        // "STAGES" => array_keys($this->arResult['ELEMENT']['STAGES'])
                                    );

                                    if ($data['TOTAL_PRICE']) {
                                        $arPropertiesPayment = array(
                                            'PAYMENT_STATUS' => PEGISTER_PAYMENT_STATUS_N,
                                            'PAYMENT_METHOD' => $this->arResult['PAYMENTS'][$data['PAYMENT']]['NAME']
                                        );
                                    }

                                    if ($arPropertiesPayment) $arProperties = array_merge($arProperties, $arPropertiesPayment);

                                    if ($coupon) {
                                        $arProperties['COUPON'] = $coupon;
                                    }

                                    // получаем категорию пользователя в данном соревновании
                                    if ($this->arResult['USER_ID']) {
                                        /*$arParameters = array('SELECT' => array('UF_AGE'), 'FIELDS' => array('ID', 'PERSONAL_GENDER'));
                                        $arUser = CUser::GetList(($by = "name"), ($order = "asc"), array('ID' => $this->arResult['USER_ID']), $arParameters)->fetch();*/

                                        if ($arUser['UF_AGE']) {

                                            // категории текущего соревнования  

                                            $arFilterCategory = array(
                                                '<=PROPERTY_AGE_FROM' => $arUser['UF_AGE'],
                                                '>=PROPERTY_AGE_TO' => $arUser['UF_AGE'],
                                                'PROPERTY_GENDER_VALUE' => $arUser['PERSONAL_GENDER'] == 'M' ? 'Мужской' : 'Женский',
                                                'ID' => $this->arResult['ELEMENT']['CATEGORIES']
                                            );

                                            $arCategory = CIBlockElement::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), $arFilterCategory, false, false, array('ID', 'IBLOCK_ID'))->GetNext();

                                            if ($arCategory['ID']) {
                                                $arProperties['CATEGORY'] = $arCategory['ID'];
                                            }
                                        }

                                        if ($arUser['UF_AGE']) {
                                            $arProperties['USER_AGE'] = $arUser['UF_AGE'];
                                        }

                                        if ($arUser['PERSONAL_GENDER']) {
                                            $arProperties['USER_GENDER'] = array('VALUE' => $arUser['PERSONAL_GENDER'] == 'M' ? GENDER_M_PROPERTY_ID : GENDER_F_PROPERTY_ID);
                                        }
                                    }

                                    $arRegisterMember = array();

                                    // если эстафета, то указано название команды. Создаем команду
                                    //pp($data, "data");
                                    $dateCreate = date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), time());
                                    if ($this->arResult['ELEMENT']['EVENT_TYPE'] == EVENT_TYPE_RELAY_RACE) {

                                        $this->arResult['REGISTER_MEMBER_ALREADY'] = array();
                                        $this->arResult['REGISTER_MEMBER_EMAIL_EMPTY'] = array();

                                        if ($data['ATHLETE']) {
                                            foreach ($data['ATHLETE'] as $key => $athlete) {
                                                if ($key > 0) {
                                                    if ($athlete['EMAIL']) {

                                                        $data['ATHLETE'][$key]['EMAIL'] = $athlete['EMAIL'] = trim($athlete['EMAIL']);

                                                        $arUser = \Bitrix\Main\UserTable::getList(array(
                                                            'select'  => array('ID'),
                                                            'filter'  => array('EMAIL' => $athlete['EMAIL'])
                                                        ))->fetch();

                                                        if ($arUser['ID']) {
                                                            $arFilter = array("IBLOCK_ID" => $this->arParams['MEMBERS_IBLOCK_ID'], "PROPERTY_USER" => $arUser['ID'], "PROPERTY_EVENT" => $this->arResult['ELEMENT']['ID'], "ACTIVE" => "Y");
                                                            if (CIBlockElement::GetList(array(), $arFilter, false, false, array('ID'))->SelectedRowsCount() > 0) {
                                                                $data['ATHLETE'][$key]['REGISTER_MEMBER_ALREADY'] = 'Y';
                                                                $this->arResult['REGISTER_MEMBER_ALREADY'] = $key;
                                                            }
                                                        }
                                                    } else {
                                                        $data['ATHLETE'][$key]['REGISTER_MEMBER_EMAIL_EMPTY'] = 'Y';
                                                        $this->arResult['REGISTER_MEMBER_EMAIL_EMPTY'] = $key;
                                                    }
                                                }
                                            }
                                        }

                                        if ($data['COMMAND_NAME'] && !$this->arResult['REGISTER_MEMBER_ALREADY'] && !$this->arResult['REGISTER_MEMBER_EMAIL_EMPTY']) {

                                            $arCommand = array(
                                                "IBLOCK_ID"       => $this->arParams['COMMAND_IBLOCK_ID'],
                                                "NAME"            => $data['COMMAND_NAME'],
                                                "ACTIVE"          => "N",
                                                "DATE_CREATE"     => $dateCreate,
                                                "ACTIVE_FROM"     => $dateCreate,
                                            );

                                            $el = new CIBlockElement;
                                            if ($commandID = $el->Add($arCommand)) {
                                                $i = 1;
                                                $arUsersInCommand = array();
                                                $captain = '';

                                                $registrationUser = '';

                                                foreach ($data['ATHLETE'] as $key => $athlete) { //pp($athlete);
                                                    $arNewProps = array();
                                                    // если нет email в передаваемых из формы данных, 
                                                    // то этот пользователь имеет аккаунт и регистрирует остальных
                                                    if ($key == 0) {
                                                        $arNewProps = $arProperties;
                                                        //if($key == 1) 
                                                        $registrationUser = $arProperties['USER'];
                                                    }

                                                    // остальные участники
                                                    else {
                                                        if ($athlete['EMAIL']) {

                                                            // проверяем по email, есть ли аккаунт у участника
                                                            $arUser = \Bitrix\Main\UserTable::getList(array(
                                                                'select'  => array('ID'),
                                                                'filter'  => array('EMAIL' => $athlete['EMAIL'])
                                                            ))->fetch();

                                                            // регистрируем нового пользователя
                                                            $userID = '';
                                                            if (!$arUser['ID']) {
                                                                $password = randString(10);

                                                                // формируем логин
                                                                $userLogin = $athlete['EMAIL'];

                                                                // проверка логина на уже зарегистрированные
                                                                if (CUser::GetByLogin($userLogin)->Fetch()) {
                                                                    $userLogin = $login = $data['NAME'] ? translit($data['NAME']) : $userLogin;
                                                                    $i = 1;
                                                                    while (CUser::GetByLogin($userLogin)->Fetch()) {
                                                                        $userLogin = $login . $i;
                                                                        $i++;
                                                                    }
                                                                }

                                                                $arRegisterResult = $USER->Register($userLogin, trim($athlete['NAME']), trim($athlete['LAST_NAME']), $password, $password, $athlete['EMAIL']);

                                                                if ($arRegisterResult['TYPE'] == 'OK') {

                                                                    $userID = $arRegisterResult['ID'];

                                                                    $user = new CUser;
                                                                    //$data['WORK_COMPANY'] = trim($data['WORK_COMPANY']);
                                                                    $arNewUserFields = array(
                                                                        "PERSONAL_GENDER" => $athlete['PERSONAL_GENDER'],
                                                                        "SECOND_NAME"   => trim($athlete['SECOND_NAME']),
                                                                        "PERSONAL_CITY" => trim($athlete['PERSONAL_CITY']),
                                                                        "PERSONAL_PHONE" => trim($athlete['PERSONAL_PHONE']),
                                                                        "UF_CLUB" => array(trim($athlete['UF_CLUB']))
                                                                    );

                                                                    $user->Update($userID, $arNewUserFields);
                                                                }
                                                            } else {
                                                                $userID = $arUser['ID'];
                                                            }

                                                            if ($userID) {
                                                                $arNewProps = array(
                                                                    "EVENT" => $data['ELEMENT_ID'],
                                                                    "USER" => $userID,
                                                                );

                                                                if ($registrationUser) $arNewProps['REGISTRATION_USER'] = $registrationUser;
                                                            }

                                                            $athlete['UF_AGE'] = trim($athlete['UF_AGE']);
                                                            if ($athlete['UF_AGE']) {
                                                                $arNewProps['USER_AGE'] = $athlete['UF_AGE'];
                                                            }

                                                            if ($athlete['PERSONAL_GENDER']) {
                                                                $arNewProps['USER_GENDER'] = array('VALUE' => $athlete['PERSONAL_GENDER'] == 'M' ? GENDER_M_PROPERTY_ID : GENDER_F_PROPERTY_ID);
                                                            }
                                                        }
                                                    }
                                                    $arNewProps['ADDITIONAL_FIELD'] = $athlete['ADDITIONAL_FIELD'];
                                                    if ($arNewProps['USER']) {
                                                        $arNewProps['STAGES'] = array($athlete['STAGES']);
                                                        $arNewProps['COMMAND'] = $commandID;

                                                        $arRegisterMember[$key] = array(
                                                            "IBLOCK_ID"       => $this->arParams['MEMBERS_IBLOCK_ID'],
                                                            "PROPERTY_VALUES" => $arNewProps,
                                                            "NAME"            => $arNewProps['USER'] . '-' . $data['ELEMENT_ID'],
                                                            "ACTIVE"          => "N",
                                                            "DATE_CREATE"     => $dateCreate,
                                                            "ACTIVE_FROM"     => $dateCreate,
                                                        );

                                                        $arUsersInCommand[] = $arNewProps['USER'];
                                                        if ($athlete['CAPTAIN']) {
                                                            $captain = $arNewProps['USER'];
                                                        }
                                                    }
                                                }
                                                krsort($arRegisterMember);

                                                // заносим данные об участниках в команду
                                                $el = new CIBlockElement;

                                                $arUpdateCommand = array();

                                                if ($arUsersInCommand) {
                                                    $arUpdateCommand['USERS'] = $arUsersInCommand;
                                                }

                                                if ($captain) {
                                                    $arUpdateCommand['CAPTAIN'] = $captain;
                                                }

                                                if ($arUpdateCommand) {
                                                    $el->Update($commandID, array('PROPERTY_VALUES' => $arUpdateCommand));
                                                }
                                            }
                                        }
                                    } else {

                                        $arProperties['STAGES'] = array_keys($this->arResult['ELEMENT']['STAGES']);
                                        // $arProperties['DISTANCE'] = array_keys($this->arResult['ELEMENT']['STAGES']);

                                        if (empty($arProperties['TOTAL_PRICE'])) {
                                            $arProperties['PAYMENT_STATUS'] = array('VALUE' => PEGISTER_PAYMENT_STATUS_N);
                                        }
                                        $arProperties["ADDITIONAL_FIELD"] = $data["ADDITIONAL_FIELD"];

                                        $arRegisterMember[] = array(
                                            "IBLOCK_ID"       => $this->arParams['MEMBERS_IBLOCK_ID'],
                                            "PROPERTY_VALUES" => $arProperties,
                                            "NAME"            => $this->arResult['USER_ID'] . '-' . $data['ELEMENT_ID'],
                                            // "ACTIVE"          => $arProperties['TOTAL_PRICE'] ? "N" : "Y", // если стоимость 0, то регистрация активна
                                            "DATE_CREATE"     => $dateCreate,
                                            "ACTIVE"          => "N",
                                            "ACTIVE_FROM"     => $dateCreate,
                                        );
                                    }

                                    if ($arRegisterMember) {
                                        $cntMembers = count($arRegisterMember);
                                        $j = 1;
                                        $arNewRegisters = array();

                                        foreach ($arRegisterMember as $oneRegister) {

                                            $el = new CIBlockElement;

                                            if ($elementID = $el->Add($oneRegister)) {

                                                $arNewRegisters[] = $elementID;

                                                if ($j == $cntMembers) {

                                                    $this->arResult['SEND_FORM'] = 'Y';

                                                    $description = 'Оплата участия в соревновании "' . $this->arResult['ELEMENT']['NAME'] . '"';

                                                    if ($data['TOTAL_PRICE'] > 0) {
                                                        switch ($data['PAYMENT']) {
                                                            case 1:
                                                                if (Loader::includeModule('sale')) {

                                                                    if ($result = CSaleUserAccount::UpdateAccount(
                                                                        $this->arResult['USER_ID'],
                                                                        -1 * $data['TOTAL_PRICE'],
                                                                        COption::GetOptionString("sale", "default_currency"),
                                                                        $description,
                                                                    )) {
                                                                        header('Location: ' . $returnUrl . '?paymentID=' . $elementID, true);
                                                                        //die;
                                                                    }
                                                                }
                                                                break;

                                                            default:
                                                                // по умолчанию сбер
                                                                payment($data['TOTAL_PRICE'], $elementID, $description, $returnUrl, $failUrl);
                                                                break;
                                                        }
                                                    } else {
                                                        header('Location: ' . $returnUrl . '?paymentID=' . $elementID, true);
                                                    }
                                                }
                                            } else {
                                                $this->arResult['RESULT_ERROR']['REGISTER_ADD'] = 'N';
                                            }

                                            $j++;
                                        }
                                    }
                                }
                            }
                        } // else {
                        if (
                            !$data['ELEMENT_ID']
                            || $this->arResult['RESULT_ERROR']['USER_BALANCE']
                            || $this->arResult['REGISTER_MEMBER_ALREADY']
                            || $this->arResult['REGISTER_MEMBER_EMAIL_EMPTY']
                        ) {
                            $this->arResult['SEND_FORM'] = 'N';

                            // поля формы с личными данными
                            if ($this->arParams['SHOW_FIELDS']) {
                                $this->arResult['SHOW_FIELDS'] = $this->arParams['SHOW_FIELDS'];

                                if ($this->arParams['USER_PROPERTY']) {
                                    $this->arResult['SHOW_FIELDS'] = array_merge($this->arResult['SHOW_FIELDS'], $this->arParams['USER_PROPERTY']);
                                }
                            } elseif ($this->arParams['USER_PROPERTY']) {
                                $this->arResult['SHOW_FIELDS'] = $this->arParams['USER_PROPERTY'];
                            }

                            $arFieldsSort = array(
                                'LOGIN'            => 10,
                                'PASSWORD'         => 20,
                                'CONFIRM_PASSWORD' => 30,
                                'LAST_NAME'        => 40,
                                'NAME'             => 50,
                                'SECOND_NAME'      => 60,
                                'UF_AGE'           => 70,
                                'PERSONAL_CITY'    => 80,
                                'PERSONAL_PHONE'   => 90,
                                'EMAIL'            => 100,
                                'UF_CLUB'          => 110,
                                'PERSONAL_GENDER'  => 120
                            );

                            // получение максимального значения сортировки
                            $maxSort = max($arFieldsSort) + 10;
                            $arNewShowFields = array();

                            foreach ($this->arResult["SHOW_FIELDS"] as &$field) {
                                $arNewShowFields[$arFieldsSort[$field] ?: $maxSort] = $field;
                            }
                            if ($arNewShowFields) $this->arResult["SHOW_FIELDS"] = $arNewShowFields;
                            // сортируем по ключу
                            ksort($this->arResult["SHOW_FIELDS"]);

                            // обязательные поля
                            if ($this->arParams['REQUIRED_FIELDS']) {
                                $this->arResult['REQUIRED_FIELDS'] = $this->arParams['SHOW_FIELDS'];

                                if ($this->arParams['USER_PROPERTY']) {
                                    $this->arResult['REQUIRED_FIELDS'] = array_merge($this->arResult['REQUIRED_FIELDS'], $this->arParams['USER_PROPERTY']);
                                }
                            } elseif ($this->arParams['USER_PROPERTY']) {
                                $this->arResult['REQUIRED_FIELDS'] = $this->arParams['USER_PROPERTY'];
                            }

                            // дополнительные услуги
                            $arServices = array();
                            if ($this->arResult['ELEMENT']['PROPERTY_SERVICES_VALUE']) {

                                if ($this->arParams['SERVICES_IBLOCK_ID']) {
                                    $arSelect = array("ID", "NAME", "PREVIEW_TEXT", "PROPERTY_PRICE");
                                    $arFilter = array("IBLOCK_ID" => $this->arParams['SERVICES_IBLOCK_ID'], "ACTIVE" => "Y", "ID" => $this->arResult['ELEMENT']['PROPERTY_SERVICES_VALUE']);
                                    $rsServices = CIBlockElement::GetList(array('SORT' => "ASC"), $arFilter, false, false, $arSelect);
                                    while ($arService = $rsServices->GetNext()) {
                                        $arServices[$arService['ID']] = $arService;
                                    }
                                }

                                $this->arResult['SERVICES'] = $arServices;
                            }

                            // данные пользователя

                            if ($this->arResult['USER_ID'] && $this->arParams['SHOW_FIELDS']) {
                                // клуб
                                if (in_array('UF_CLUB', $this->arParams['USER_PROPERTY'])) {

                                    $rsClubField = CIBlockElement::GetList(array('NAME' => 'ASC'), array('IBLOCK_ID' => CLUBS_IBLOCK_ID, "ACTIVE" => "Y"), false, false, array('ID', 'NAME'));

                                    $arClub = array();
                                    while ($arClubField = $rsClubField->GetNext()) {
                                        $arClub[$arClubField['ID']] = $arClubField['NAME'];
                                    }
                                    if ($arClub) $this->arResult['UF_CLUB_LIST'] = $arClub;

                                    /*$arClubField = CUserTypeEntity::GetList( array(), array('FIELD_NAME' => 'UF_CLUB'))->Fetch();
                                    $arClub = array();

                                    if($arClubField['ID']) {
                                        $rsGender = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => $arClubField['ID']));
                                        while($arGender = $rsGender->GetNext()) {
                                            $arClub[$arGender['ID']] = $arGender['VALUE'];
                                        }
                                        if($arClub) $this->arResult['UF_CLUB_LIST'] = $arClub;
                                    }*/
                                }

                                $arFilter = array("ID" => $this->arResult['USER_ID']);
                                $arParameters['FIELDS'] = array_merge(array('ID'), $this->arParams['SHOW_FIELDS']);

                                if ($this->arParams['USER_PROPERTY']) {
                                    $arParameters['SELECT'] = $this->arParams['USER_PROPERTY'];
                                }

                                // по дате рождения вычисляем возраст
                                if (!in_array('PERSONAL_BIRTHDAY', $arParameters['FIELDS'])) {
                                    $arParameters['FIELDS'][] = 'PERSONAL_BIRTHDAY';
                                }

                                $rsUsers = CUser::GetList(($by = "name"), ($order = "asc"), $arFilter, $arParameters);

                                while ($arUserInfo = $rsUsers->GetNext()) {
                                    //print_r($arUserInfo['UF_CLUB']);
                                    if ($arUserInfo['UF_CLUB']) {
                                        $arUserInfo['~UF_CLUB'] = $arClub[$arUserInfo['UF_CLUB'][0]];
                                    }

                                    //возраст
                                    if ($arUserInfo['PERSONAL_BIRTHDAY']) {
                                        $arUserInfo['UF_AGE'] = getUserAge($arUserInfo['PERSONAL_BIRTHDAY']);
                                    }

                                    $this->arResult['USER'] = $arUserInfo;
                                }
                            }

                            if (!$data['ATHLETE'] && $this->arResult['ELEMENT']['EVENT_TYPE'] == EVENT_TYPE_RELAY_RACE) {
                                $data['ATHLETE'][] = array();
                            }
                            // pp($data);
                            if ($data) $this->arResult['DATA_SEND'] = $data;
                        }
                    } else {
                        $this->arResult['REGISTER_ALREADY'] = "Y";
                    }
                } else {
                    $this->arResult['PAYMENT_STATUS'] = $this->arParams['PAYMENT_STATUS'];

                    AddMessage2Log("PAYMENT_STATUS: " . $this->arParams['PAYMENT_STATUS']);

                    $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_COMMAND", "PROPERTY_EVENT", "PROPERTY_REGISTRATION_CANCEL", "PROPERTY_PAYMENT_STATUS");

                    AddMessage2Log("SBERBANK_ORDER_ID: " . $this->arParams['SBERBANK_ORDER_ID']);
                    AddMessage2Log("PAYMENT_ID: " . $this->arParams['PAYMENT_ID']);

                    if ($this->arParams['SBERBANK_ORDER_ID']) {

                        $arRegisterFilter = array('PROPERTY_SBERBANK_ORDER_ID' => $this->arParams['SBERBANK_ORDER_ID'], 'ACTIVE' => 'N');
                        AddMessage2Log("arRegisterFilter: " . json_encode($arRegisterFilter));
                        $arRegister = CIBlockElement::GetList(array(), $arRegisterFilter, false, false, $arSelect)->fetch();

                        //$arRegister = CIBlockElement::GetList(array(), array('PROPERTY_SBERBANK_ORDER_ID' => $this->arParams['SBERBANK_ORDER_ID']), false, false, array("ID", "NAME", "IBLOCK_ID"))->GetNext();

                    } elseif ($this->arParams['PAYMENT_ID']) {
                        /*$arElement = \Bitrix\Iblock\ElementTable::getList(array(
                            'filter' => array('ID' => $this->arParams['PAYMENT_ID']),
                            'select' => array('ID')
                        ))->fetch();

                        if($arElement['ID']) {
                            $arRegister['ID'] = $arElement['ID'];
                        }*/

                        $arRegister = CIBlockElement::GetList(array(), array('ID' => $this->arParams['PAYMENT_ID'], 'ACTIVE' => 'N', 'PROPERTY_SBERBANK_ORDER_ID' => false), false, false, $arSelect)->fetch();
                    }

                    AddMessage2Log("arRegister: " . json_encode($arRegister));

                    AddMessage2Log("PAYMENT_STATUS(arResult): " . $this->arResult['PAYMENT_STATUS']);

                    if ($arRegister['ID'] && $this->arResult['PAYMENT_STATUS'] == 'success') {

                        AddMessage2Log("оплата прошла");
                        //CIBlockElement::SetPropertyValuesEx($arRegister['ID'], false, array('PAYMENT_STATUS' => $this->arResult['PAYMENT_STATUS'] == 'success' ? 25 : 26));

                        //CIBlockElement::SetPropertyValuesEx($arRegister['ID'], false, array('PAYMENT_STATUS' => PEGISTER_PAYMENT_STATUS_Y));

                        //$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_COMMAND", "PROPERTY_EVENT", "PROPERTY_REGISTRATION_CANCEL", "PROPERTY_PAYMENT_STATUS");
                        //$arElementRegister = CIBlockElement::GetList(array(), array("ID" => $arRegister['ID'], 'ACTIVE' => 'N'), false, false, $arSelect)->fetch();

                        $arElementRegister = $arRegister;

                        AddMessage2Log("REGISTRATION_CANCEL: " . $arElementRegister['PROPERTY_REGISTRATION_CANCEL_VALUE']);
                        AddMessage2Log("PAYMENT_STATUS: " . $arElementRegister['PROPERTY_PAYMENT_STATUS_ENUM_ID']);

                        if (!$arElementRegister['PROPERTY_REGISTRATION_CANCEL_VALUE'] && $arElementRegister['PROPERTY_PAYMENT_STATUS_ENUM_ID'] == PEGISTER_PAYMENT_STATUS_N) {

                            AddMessage2Log("Оплачено!");

                            CIBlockElement::SetPropertyValuesEx($arRegister['ID'], false, array('PAYMENT_STATUS' => PEGISTER_PAYMENT_STATUS_Y));

                            // если регистрация привязана к команде, то активируем команду и ее участников
                            if ($arElementRegister['PROPERTY_COMMAND_VALUE']) {

                                $eventID = $arElementRegister['PROPERTY_EVENT_VALUE'];

                                $el = new CIBlockElement;
                                $el->Update($arElementRegister['PROPERTY_COMMAND_VALUE'], array('ACTIVE' => "Y"));

                                $rsCommandMembers = CIBlockElement::GetList(array(), array("ID" => $arElementRegister['PROPERTY_COMMAND_VALUE']), false, false, array("ID", "IBLOCK_ID", "PROPERTY_USERS"));
                                // получаем ID участников команды
                                $arCommandMembersIDs = array();
                                while ($arCommandMember = $rsCommandMembers->GetNext()) {
                                    if ($arCommandMember['PROPERTY_USERS_VALUE']) {
                                        if (!in_array($arCommandMember['PROPERTY_USERS_VALUE'], $arCommandMembersIDs)) {
                                            $arCommandMembersIDs[] = $arCommandMember['PROPERTY_USERS_VALUE'];
                                        }
                                    }
                                }

                                // получаем регистрации участников к данному соревнованию
                                if ($arCommandMembersIDs && $eventID) {
                                    $arFilter = array("PROPERTY_USER" => $arCommandMembersIDs, "PROPERTY_EVENT" => $eventID);
                                    $rsCommandRegisters = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "IBLOCK_ID"));
                                    while ($arCommandRegister = $rsCommandRegisters->GetNext()) {
                                        // активируем участников
                                        $el->Update($arCommandRegister['ID'], array('ACTIVE' => "Y"));
                                    }
                                }
                            }

                            $el = new CIBlockElement;
                            $el->Update($arRegister['ID'], array('ACTIVE' => "Y"));
                        }
                    } else {
                        AddMessage2Log("оплата не прошла");
                    }

                    if (!$arRegister) {
                        header('Location: ' . $eventUrl, true);
                    }
                }
            }
        }

        $this->includeComponentTemplate();
    }
}
