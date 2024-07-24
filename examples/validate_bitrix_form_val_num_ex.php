<?
class CFormCustomValidatorNumberEx
{
    function GetDescription()
    {
        return array(
            "NAME"            => "custom_words_ex",                                   // идентификатор
            "DESCRIPTION"     => "Только буквы",                                 // наименование
            "TYPES"           => array("text", "textarea"),                            // типы полей
            "SETTINGS"        => array("CFormCustomValidatorNumberEx", "GetSettings"), // метод, возвращающий массив настроек
            "CONVERT_TO_DB"   => array("CFormCustomValidatorNumberEx", "ToDB"),        // метод, конвертирующий массив настроек в строку
            "CONVERT_FROM_DB" => array("CFormCustomValidatorNumberEx", "FromDB"),      // метод, конвертирующий строку настроек в массив
            "HANDLER"         => array("CFormCustomValidatorNumberEx", "DoValidate")   // валидатор
        );
    }
    function GetSettings()
    {
        return array(
            "REGEX" => array(
                "TITLE"   => "Регулярное выражение",
                "TYPE"    => "TEXT",
                "DEFAULT" => "/^[а-яёa-z\s]+$/ius",
            ),
        );
    }
    function ToDB($arParams)
    {
        // возвращаем сериализованную строку
        return serialize($arParams);
    }
    function FromDB($strParams)
    {
        // никаких преобразований не требуется, просто вернем десериализованный массив
        return unserialize($strParams);
    }

    function DoValidate($arParams, $arQuestion, $arAnswers, $arValues)
    {
        global $APPLICATION;

        foreach ($arValues as $value) {
            // пустые значения пропускаем
            if (empty($value)) {
                continue;
            }
           
            if (!preg_match($arParams["REGEX"], $value)) {
                // echo "Имя может содержать только русские / латинские символы, пробел, цифры и знак _";
                $APPLICATION->ThrowException("#FIELD_NAME#: Может содержать только русские / латинские символы, пробел");
                return false;
            }
        }

        // все значения прошли валидацию, вернем true
        return true;
    }
}
// установим метод CFormCustomValidatorNumberEx в качестве обработчика события
AddEventHandler("form", "onFormValidatorBuildList", array("CFormCustomValidatorNumberEx", "GetDescription"));
