<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$module_id = 'webprostor.smtp';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);

if ($moduleAccessLevel == "D")
{
	ShowError(GetMessage('WEBPROSTOR_SMTP_GADGET_ERROR_NO_ACCESS'));
	return;
}

if(!CModule::IncludeModule($module_id)) {
	return;
}

$LOG_ERRORS = COption::GetOptionString($module_id, "LOG_ERRORS", 'N');
if($LOG_ERRORS == "N")
{
	ShowError(GetMessage('WEBPROSTOR_SMTP_GADGET_ERROR_LOG_ERRORS'));
	return;
}

$arColors = [
	'TYPES' => [
		"ALL" => '444444',
		"OK" => '008000',
		"ERROR" => 'ff0000',
	],
	'ORIGINAL' => [
		'444444',
		'008000',
		'ff0000',
	]
];

$arTypes = $arGadgetParams['TYPES'];
if (empty($arTypes) || !is_array($arTypes)) {
	$arTypes = array("ALL", "OK", "ERROR");
}

if (intval($arGadgetParams['WIDTH'])<=0) {
	$width = 500;
} else {
	$width = intval($arGadgetParams['WIDTH']);
}
if (intval($arGadgetParams['HEIGHT'])<=0) {
	$height = 300;
} else {
	$height = intval($arGadgetParams['HEIGHT']);
}

$n = intval($arGadgetParams['DAYS']);
if ($n < 1) {
	$n = 7;
}

$dyn = array();
$GridX = array();

$arResult['ALL_COUNT'] = 0;

$cDataLogs = new CWebprostorSmtpLogs;
for($n;$n>0;$n--)
{
	$dateFrom = MakeTimeStamp(date("d.m.Y"), "DD.MM.YYYY HH:MI:SS");
	$stmp = AddToTimeStamp(["DD" => -($n-1)], $dateFrom);
	$stmp2 = AddToTimeStamp(["DD" => -($n-1), "HH" => 23, "MI" => 59, "SS" => 59], $dateFrom);

	$startDate = ConvertTimeStamp($stmp, 'SHORT');
	$endDate = ConvertTimeStamp($stmp2, 'FULL');
	
	if(is_array($arTypes))
	{
		foreach($arTypes as $type)
		{
			$arFilter = array(
				'>=DATE_CREATE'=> $startDate,
				'<=DATE_CREATE'=> $endDate,
			);
			switch($type)
			{
				case("ERROR"):
					$arFilter["SENDED"] = "N";
					break;
				case("OK"):
					$arFilter["SENDED"] = "Y";
					break;
				default:
					break;
			}
			if($arGadgetParams['SITE_ID'])
				$arFilter["SITE_ID"] = $arGadgetParams['SITE_ID'];
			
			$dbLog = $cDataLogs->GetList(Array('DATE' => 'ASC'), $arFilter, ['ID']);
			$dyn[$type] = 0;
			$arrY = array();
			$rateNew = 0;
			
			$strDate = strtotime($startDate);
			$current = $dbLog->SelectedRowsCount();
			$arResult['ALL_COUNT'] += $current;
			$arrY[$type][$strDate] = $current;
			$grid[$type][] = $strDate;
			if ($rateNew != $current){
				$dyn[$type] = $current-$rateNew;
				$rateNew = $current;
			}

			if($arrY[$type]) {
				$arResult['DATA'][$type]['Y'][$strDate] = $current;
			}
			if($grid[$type]) {
				$GridX = array_merge($GridX,$grid[$type]);
			}

			$arResult['CURRENT'][$type] = $current;
			$arResult['DYN'][$type] = round($dyn[$type], 4);

		}
	}
}

$GridX = array_unique($GridX);
sort($GridX);

foreach($GridX as $date){
	$GridXx[$date] = ConvertTimeStamp($date,'SHORT');
}

$arAxis = [
	0 => GetMessage("WEBPROSTOR_SMTP_GADGET_DATA")
];
$arResult['GRAPH'] = array();

foreach($arResult['DATA'] as $type=>$arrType){
	$arAxis[] = GetMessage('WEBPROSTOR_SMTP_GADGET_TYPE_'.$type);
}

$arResult['GRAPH'][] = $arAxis;

foreach($GridXx as $key=>$date)
{
	$arData = array($date);
	
	foreach($arResult['DATA'] as $type=>$arrType)
	{
		$arData[] = $arResult['DATA'][$type]['Y'][$key];
	}
	$arResult['GRAPH'][] = $arData;
}

if (is_array($arResult['DATA']) && $arResult['ALL_COUNT'] > 0)
{
	if(CModule::IncludeModule('webprostor.core'))
		$blockID = "webprostor_smtp_diagram_".CWebprostorCoreFunctions::GenerateRandomCode(10);
	else
		$blockID = "webprostor_smtp_diagram";
?>
	<?
	$dataY = \Bitrix\Main\Web\Json::encode($arResult['GRAPH']);
	?>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<div id="<?=$blockID?>"></div>
	<script type="text/javascript">
		google.load('visualization', '1.0', {'packages':['corechart']});
		
		function drawCurrencyDiagram() {
			var data = google.visualization.arrayToDataTable(<?=$dataY?>);
			new google.visualization.LineChart(document.getElementById('<?=$blockID?>')).
			draw(data, {
				colors: <?=json_encode($arColors["ORIGINAL"])?>,
				curveType: 'function',
				width: <?=$width?>,
				height: <?=$height?>,
				vAxis: {
					title: '<?=CUtil::JSEscape(GetMessage('WEBPROSTOR_SMTP_GADGET_COUNT'))?>'
				},
				hAxis:{
					title: '<?=CUtil::JSEscape(GetMessage('WEBPROSTOR_SMTP_GADGET_DATA'))?>',
					baseline: 'date'
				}
			});
		}
		google.setOnLoadCallback(drawCurrencyDiagram);
	</script>
	<div>
		<table>
		<?
		$i = 0;
		foreach($arResult['DATA'] as $type=>$arr) {
			?>
			<tr>
				<td style="text-align: center; color:<?='#'.$arColors["TYPES"][$type]?>"><?=GetMessage('WEBPROSTOR_SMTP_GADGET_TYPE_'.$type)?></td>
				<td style="text-align: center;color:<?='#'.$arColors["TYPES"][$type]?>"><?=$arResult['CURRENT'][$type]?></td>
				<?if($arResult['DYN'][$type]>0){?>
					<td style="text-align: center;color:green">(+<?=$arResult['DYN'][$type]?>)</td>
				<?}elseif($arResult['DYN'][$type]<0){?>
					<td style="text-align: center;color:red">(<?=$arResult['DYN'][$type]?>)</td>
				<?}elseif($arResult['DYN'][$type]==0){?>
					<td style="text-align: center;color:gray">(<?=$arResult['DYN'][$type]?>)</td>
				<?}?>
			</tr>
			<?
			$i++;
		}?>
		</table>
	</div>
	<a href="/bitrix/admin/webprostor.smtp_logs.php?lang=<?=LANGUAGE_ID?>" class="adm-btn"><?=GetMessage('WEBPROSTOR_SMTP_GADGET_OPEN_LOGS')?></a>
<?
}
else
{
	ShowError(GetMessage('WEBPROSTOR_SMTP_GADGET_ERROR_NO_DATA'));
}
?>