<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetPageProperty("description", "Компания \"Золотая долина\" производит чай и чайные напитки с 1992 года. Разрабатываем купажи, фасуем, упаковываем и всё это под вашей торговой маркой!");
$APPLICATION->SetPageProperty("title", "Производство чая в Москве оптом | Золотая долина");
$APPLICATION->SetTitle("Центр чайных компетенций для вашего бизнеса");
$regexp = '/^\s?(\+\s?7|8)([- ()]*\d){10}$/';
$regexp2 = '/[0-9-+\s]{1,30}$/'; // only digits, sign(-) plus, whitespace

// Правильные
$correctNumbers = [
    '84951234567',  '+74951234567', '8-495-1-234-567',
    ' 8 (8122) 56-56-56', '8-911-1234567', '8 (911) 12 345 67',
    '8-911 12 345 67', '8 (911) - 123 - 45 - 67', '+ 7 999 123 4567',
    '8 ( 999 ) 1234567', '8 999 123 4567', '+7(999)-999-99-99'
];

// Неправильные: 
$incorrectNumbers = [
    '02', '84951234567 позвать люсю', '849512345', '849512345678',
    '8 (409) 123-123-123', '7900123467', '5005005001', '8888-8888-88',
    '84951a234567', '8495123456a', '+1 234 5678901', '+8 234 5678901',
    '7 234 5678901'
];


// Проверяем правильные номера
foreach ($correctNumbers as $key) {
    if (preg_match($regexp, $key)) {
        echo $key . "\n";
    }
}
$regexpMame = '/^[а-яё]{3,30}|[a-z]{3,30}$/iu';

$correctNames = ["Иван", "Анна", "Тимур", "Александр", "Max"," dd"," d  "];



foreach ($correctNames as $name) {
    echo "check: " . $name  . "<br>";
    if (preg_match($regexpMame, $name)) {
        echo $name  . "<br>";
    }
}

?> 

<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>