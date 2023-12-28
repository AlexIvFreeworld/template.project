<?
// create_r52
//debug($_GET);
define(FIRSTLEAPYEAR, 2012);
define(LASTYEAR, 3000);

$arLeapYears = array();

for ($i = FIRSTLEAPYEAR; $i < LASTYEAR; $i += 4) {
  $arLeapYears[] = $i;
}
$current_date = getdate();
$mday = 1;
if (!empty($_GET['month']) && !empty($_GET['year'])) {
  $month = (int)$_GET['month'];
  $year = (int)$_GET['year'];
} else {
  $month = $current_date['mon'];
  $year = $current_date['year'];
  $mday = $current_date['mday'];
}
// debug($arLeapYears);
//debug(in_array($year, $arLeapYears));
$arDayInMonth = array(
  1 => 31,
  2 => (in_array($year, $arLeapYears)) ? 29 : 28,
  3 => 31,
  4 => 30,
  5 => 31,
  6 => 30,
  7 => 31,
  8 => 31,
  9 => 30,
  10 => 31,
  11 => 30,
  12 => 31,
);
/*
$first_day = mktime(0, 0, 0, $month, 1, $year);
$last_day = mktime(23, 59, 59, $month + 1, 1 - 1);
$first_day_next_month = mktime(0, 0, 0, $month + 1, 1, $year);
$last_day_next_month = mktime(23, 59, 59, $month + 2, 1 - 1);
$first_day_prev_month = mktime(0, 0, 0, $month - 1, 1, $year);
$last_day_prev_month = mktime(23, 59, 59, $month, 1 - 1);
if ($month == 1 && $year == 2022) {
  //debug("unic");
  $last_day = mktime(23, 59, 59, $month, 31, $year);
  $first_day_prev_month = mktime(0, 0, 0, 12, 1, $year - 1);
  $last_day_prev_month = mktime(23, 59, 59, 12, 31, $year - 1);
  $first_day_next_month = mktime(0, 0, 0, $month + 1, 1, $year);
  $last_day_next_month = mktime(23, 59, 59, $month + 1, 28, $year);
}
*/
if (true) {
  $first_day = mktime(0, 0, 0, $month, $mday, $year);
  $last_day = mktime(23, 59, 59, $month, $arDayInMonth[$month], $year);
  $prevMonth = (($month - 1) == 0) ? 12 : $month - 1;
  $prevYear = (($month - 1) == 0) ? $year - 1 : $year;
  $first_day_prev_month = mktime(0, 0, 0, $prevMonth, 1, $prevYear);
  $last_day_prev_month = mktime(23, 59, 59, $prevMonth, $arDayInMonth[$prevMonth], $prevYear);
  $nextMonth = (($month + 1) == 13) ? 1 : $month + 1;
  $nextYear = (($month + 1) == 13) ? $year + 1 : $year;
  $first_day_next_month = mktime(0, 0, 0, $nextMonth, 1, $nextYear);
  // debug(gettype($nextMonth));
  // debug(gettype($arDayInMonth[$nextMonth]));
  // debug(gettype($nextYear));
  $last_day_next_month = mktime(23, 59, 59, $nextMonth, $arDayInMonth[$nextMonth], $nextYear); 
}
//debug('first_day_prev_month' . ": " . date("d-m-y", $first_day_prev_month));
//debug("last_day_prev_month" . ": " . date("d-m-y", $last_day_prev_month));
//debug("first_day" . ": " . date("d-m-y", $first_day));
//debug("last_day" . ": " . date("d-m-y", $last_day));
//debug("first_day_next_month" . ": " . date("d-m-y", $first_day_next_month));
//debug("last_day_next_month" . ": " . date("d-m-y", $last_day_next_month));

// curl -X GET "https://api.radario.ru/events" -H "api-id: XXX" -H "api-key: XXX" -H "api-version: 1.1";
if(false){
  $apiId = "481";
  $apiKey = "oKU7baNZOgwLuQl9KC3MMxJTE4rDiXxU";
  $eventId = "2078374";
  // $url = 'https://api.radario.ru/events';
  $url = 'https://api.radario.ru/events/'.$eventId;

  $headers = ["api-id: ".$apiId, "api-key: ".$apiKey, "api-version: 1.1"];
  
  $curl = curl_init(); // создаем экземпляр curl
  
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_VERBOSE, 1); 
  curl_setopt($curl, CURLOPT_POST, false); // 
  curl_setopt($curl, CURLOPT_URL, $url);
  
  $result = curl_exec($curl);
  curl_close($curl);

  $result = json_decode($result);
  debug($result);
}


$arResult["ITEMS_CURRENT"] = [];
$arResult["ITEMS_NEXT"] = [];
$arResult["ITEMS_PREV"] = [];
foreach ($arResult["ITEMS"] as $arItem) {
  if (strtotime($arItem['PROPERTIES']['DATE_TIME']['VALUE']) <= $last_day && strtotime($arItem['PROPERTIES']['DATE_TIME']['VALUE']) >= $first_day) $arResult["ITEMS_CURRENT"][] = $arItem;
  if (strtotime($arItem['PROPERTIES']['DATE_TIME']['VALUE']) <= $last_day_next_month && strtotime($arItem['PROPERTIES']['DATE_TIME']['VALUE']) >= $first_day_next_month) $arResult["ITEMS_NEXT"][] = $arItem;
  if (strtotime($arItem['PROPERTIES']['DATE_TIME']['VALUE']) <= $last_day_prev_month && strtotime($arItem['PROPERTIES']['DATE_TIME']['VALUE']) >= $first_day_prev_month) $arResult["ITEMS_PREV"][] = $arItem;
}