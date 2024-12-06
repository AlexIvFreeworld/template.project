<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if($arResult['QUESTIONS']['SPECIALIST']){
    $arStaffs = array();

	$propertyCode = $arResult['QUESTIONS']['SPECIALIST']['CODE'];
    if($propertyCode){
        $arProperty = CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $propertyCode))->Fetch();
        if($arProperty){
            $arStaffs = CAllcorp3MedcCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CAllcorp3MedcCache::GetIBlockCacheTag($arProperty['LINK_IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('IBLOCK_ID' => $arProperty['LINK_IBLOCK_ID'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, false, array('ID', 'NAME',  'PROPERTY_SPECIALIZATION'));
        }
    }

	if($arStaffs){
        uasort($arStaffs, function($arA, $arB){
            return $arA['NAME'] <=> $arB['NAME'];
        });

		$strValues = '<option data-id="all" value=""></option>';
		
		if(is_array($arStaffs)){
			foreach($arStaffs as $arStaff){
				if($arStaff['NAME']){
					$arResult['STAFFS'][] = $arStaff;
					$strValues .= '<option data-id="'.$arStaff['ID'].'" value="'.$arStaff['ID'].'">'.$arStaff['NAME'].'</option>';
				}
			}
		}
		else{
			if($arStaffs['NAME']){
				$arResult['STAFFS'][] = $arStaffs;
				$strValues .= '<option data-id="'.$arStaffs['ID'].'" value="'.$arStaffs['ID'].'">'.$arStaffs['NAME'].'</option>';
			}
		}
		
        $required = $arResult['QUESTIONS']['SPECIALIST']['IS_REQUIRED'] == 'Y' ? ' required' : '';
		$arResult['QUESTIONS']['SPECIALIST']['HTML_CODE'] = '<div class="wrap"><select id="'.$arResult['QUESTIONS']['SPECIALIST']['CODE'].'" class="form-control '.$required.'" name="'.$arResult['QUESTIONS']['SPECIALIST']['CODE'].'">'.$strValues.'</select></div>';
	}
}

if(
    $arResult['QUESTIONS']['SPECIALIZATION'] &&
    isset($arResult['STAFFS']) && 
    $arResult['STAFFS']
){
	$arSpecializations = array();

	foreach($arResult['STAFFS'] as $arStaff){
		if($arStaff['PROPERTY_SPECIALIZATION_VALUE']){
			if(is_array($arStaff['PROPERTY_SPECIALIZATION_VALUE'])){
				foreach($arStaff['PROPERTY_SPECIALIZATION_VALUE'] as $value){
					$arSpecializations[$value]['VALUE'] = $value;
					$arSpecializations[$value]['STAFFS_ID'][] = $arStaff['ID'];
				}
			}
			else{
				$arSpecializations[$arStaff['PROPERTY_SPECIALIZATION_VALUE']]['VALUE'] = $arStaff['PROPERTY_SPECIALIZATION_VALUE'];
				$arSpecializations[$arStaff['PROPERTY_SPECIALIZATION_VALUE']]['STAFFS_ID'][] = $arStaff['ID'];
			}
		}
	}
	
	if($arSpecializations){
        ksort($arSpecializations);

		$strValues = '<option data-id="all" value=""></option>';
		foreach($arSpecializations as $arSpecialization){
			$strValues .= '<option value="'.$arSpecialization['VALUE'].'" data-id="'.implode(',', $arSpecialization['STAFFS_ID']).'">'.$arSpecialization['VALUE'].'</option>';
		}
	}
	
    $required = $arResult['QUESTIONS']['SPECIALIZATION']['IS_REQUIRED'] == 'Y' ? ' required' : '';
	$arResult['QUESTIONS']['SPECIALIZATION']['HTML_CODE'] = '<div class="wrap"><select id="SPECIALIZATION" class="form-control'.($arResult['QUESTIONS']['SPECIALIZATION']['IS_REQUIRED'] == 'Y' ? ' required' : '').'" name="SPECIALIZATION">'.$strValues.'</select></div>';
}
else{
	unset($arResult['QUESTIONS']['SPECIALIZATION']);
}
?>