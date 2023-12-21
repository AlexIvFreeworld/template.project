<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Стальная полоса");
CModule::IncludeModule("iblock");
CModule::IncludeModule("main");
// R52::debug($_GET);
// R52::debug($APPLICATION->GetCurPage());
// R52::debug(count( explode("/",$APPLICATION->GetCurPage())));
$arCurPage = explode("/",$APPLICATION->GetCurPage());
$countEl = count($arCurPage);
$lastEl = $arCurPage[array_key_last($arCurPage)];
// R52::debug("lastel: ". $lastEl . " countEl: " . $countEl);
if(!($countEl == 4 && empty($lastEl))){
//   LocalRedirect("/404.php", "404 Not Found");
global $APPLICATION;
$APPLICATION->RestartBuffer();
CHTTP::SetStatus("404 Not Found");
include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/header.php");
include($_SERVER["DOCUMENT_ROOT"].PATH_TO_404);
include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/footer.php");
return;
}
$code = $_REQUEST["CODE"];
$getCat = CIBlockSection::GetList(array(),array("IBLOCK_ID" => 12, "DEPTH_LEVEL" => 2, "CODE" => $code),false,array("ID","IBLOCK_ID","CODE","NAME","PICTURE","DESCRIPTION", "UF_H1", "UF_DIRS_WITH_ITEMS","UF_PRICE","UF_ICON","UF_H2"))->fetch();
$icon = CFile::getPath($getCat["UF_ICON"]);
//$getCat = CIBlockSection::GetList(array(),array("IBLOCK_ID" => 12, "DEPTH_LEVEL" => 2, "CODE" => $code),false)->fetch();

$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(
	12, // ID инфоблока
	$getCat["ID"] // ID элемента
);
$arElMetaProp = $ipropValues->getValues();
//print_r($getCat);


$APPLICATION->SetPageProperty("description",  $arElMetaProp["SECTION_META_DESCRIPTION"]);
$APPLICATION->SetPageProperty("keywords",  $arElMetaProp["SECTION_META_KEYWORDS"]);
if($arElMetaProp["SECTION_META_TITLE"]){
	$APPLICATION->SetTitle($arElMetaProp["SECTION_META_TITLE"]);
}else{
	$APPLICATION->SetTitle($getCat["NAME"]);
}

?>
<main>
<section class="general__catalog">
<div class="container">
	<h2 class="title2">каталог продукции</h2>
	<div class="owl-carousel owl-theme general__catalog-slider" id="js-general-catalog-slider">
		<?$prokats = CIBlockSection::GetList(array(),array("IBLOCK_ID" => 12, "ACTIVE" => "Y", "DEPTH_LEVEL" => 1),false,array("ID","IBLOCK_ID","NAME","PICTURE","UF_LINK"));?>
		<?while($prokat = $prokats->fetch()):?>
			<div class="general__catalog-unit">
				<div class="general__catalog-head">
					<div>
						 <a href="<?=$prokat["UF_LINK"].'/'?>"><?=$prokat["NAME"]?></a>
					</div>
					<ul class="general__catalog-list">
						<?$categories = CIBlockSection::GetList(array("SORT"=>"ASC"),array("IBLOCK_ID" => $prokat["IBLOCK_ID"], "SECTION_ID" => $prokat["ID"], "ACTIVE" => "Y"),false,array("ID","IBLOCK_ID","SECTION_PAGE_URL","NAME"));?>
						<?while($category = $categories->GetNext()):?>
							<li class="general__catalog-item"> <a href="<?=$category["SECTION_PAGE_URL"]?>" class="general__catalog-link"><?=$category["NAME"]?></a> </li>
						<?endwhile;?>
					</ul>
				</div>
				<div class="general__catalog-img">
                <?$photo = CFile::ResizeImageGet($prokat["PICTURE"],array("width" => 289, "height" => 235),BX_RESIZE_IMAGE_EXACT);?>
	 <img alt="<?=$prokat["NAME"]?>" src="<?=$photo["src"]?>" class="img-responsive">
				</div>
			</div>
		<?endwhile;?>
	</div>
</div>
<button class="arrow general__catalog-prev" id="js-general-catalog-prev"></button>
<button class="arrow arrow_2 general__catalog-next" id="js-general-catalog-next"></button>
</section>

		<div class="container">

			<div class="produktsia">
				<?$catPhoto = CFile::ResizeImageGet($getCat["PICTURE"],array("width" => 300, "height" => 300),BX_RESIZE_IMAGE_EXACT);?>
				<img src="<?=$catPhoto["src"]?>" alt="<?=$getCat["NAME"]?>"/>
				<h1><?=$getCat["UF_H1"]?></h1>
				<?=$getCat["DESCRIPTION"]?>
			</div>
			<!--<a href="/pricelist/<?/* =$getCat["CODE"] */?>/" class="oformit">Перейти в прайс-лист</a>-->
			<!--	<div class="produktsia-row">
				<div class="produktsia-cell">
					<img src="img/tovar1.jpg" alt="alt" />
					<h4>Металлический лист</h4>
					<a href="#" class="oformit">Оформить заявку</a>
				</div>
				<div class="produktsia-cell">
					<img src="img/tovar2.jpg" alt="alt" />
					<h4>металические блоки</h4>
					<a href="#" class="oformit">Оформить заявку</a>
				</div>
				<div class="produktsia-cell">
					<img src="img/tovar3.jpg" alt="alt" />
					<h4>металлические трубы</h4>
					<a href="#" class="oformit">Оформить заявку</a>
				</div>
				</div>
				<div class="produktsia-row">
				<div class="produktsia-cell">
					<img src="img/tovar1.jpg" alt="alt" />
					<h4>Металлический лист</h4>
					<a href="#" class="oformit">Оформить заявку</a>
				</div>
				<div class="produktsia-cell">
					<img src="img/tovar2.jpg" alt="alt" />
					<h4>металические блоки</h4>
					<a href="#" class="oformit">Оформить заявку</a>
				</div>
				<div class="produktsia-cell">
					<img src="img/tovar3.jpg" alt="alt" />
					<h4>металлические трубы</h4>
					<a href="#" class="oformit">Оформить заявку</a>
				</div>
				</div>
				<div class="produktsia-row">
				<div class="produktsia-cell">
					<img src="img/tovar1.jpg" alt="alt" />
					<h4>Металлический лист</h4>
					<a href="#" class="oformit">Оформить заявку</a>
				</div>
				<div class="produktsia-cell">
					<img src="img/tovar2.jpg" alt="alt" />
					<h4>металические блоки</h4>
					<a href="#" class="oformit">Оформить заявку</a>
				</div>
				<div class="produktsia-cell">
					<img src="img/tovar3.jpg" alt="alt" />
					<h4>металлические трубы</h4>
					<a href="#" class="oformit">Оформить заявку</a>
				</div>
				</div>-->
				<?
				//print_r($getCat);
				if($getCat['UF_PRICE']):?>
					<div class="catalog_table">
						<h2><?=$getCat['UF_H2']?></h2>
						<p class="text_sort">Сортировать по:</p>
						<table class="price_cat">
						<colgroup width="250">
							<thead>
								<tr>
									<th style="position: relative;" class="arrow-up">Наименование
										<div class="filter_block" data-index="0" style="position: absolute;top: 30px;background: white;border: 1px solid red;display: none;"></div>
									</th>
									<th style="position: relative;" class="arrow-up">Размер
										<div class="filter_block" data-index="1" style="position: absolute;top: 30px;background: white;border: 1px solid red;display: none;"></div>
									</th>
									<th style="position: relative;" class="arrow-up">Гост
										<div class="filter_block" data-index="2" style="position: absolute;top: 30px;background: white;border: 1px solid red;display: none;"></div>
									</th>
									<th style="position: relative;" class="arrow-up">Марка
										<div class="filter_block" data-index="3" style="position: absolute;top: 30px;background: white;border: 1px solid red;display: none;"></div>
									</th>
									<th style="position: relative;" class="arrow-up">Филиал
										<div class="filter_block" data-index="4" style="width: max-content;position: absolute;top: 30px;background: white;border: 1px solid red;display: none;"></div>
									</th>
									<th>Цена за п.м./кв.м.</th>
									<th>Цена за шт.</th>
								</tr>
							</thead>
							<tbody>

                            <?
                            $arFilials = array();
                            $filials = CIBlockElement::GetList(array("sort" => "asc"),array("IBLOCK_ID" => 4, "ACTIVE" => "Y","PROPERTY_TYPE" => 18),false,false,array("ID","IBLOCK_ID","NAME","CODE"));
                            while($filial = $filials->fetch()):
                                $arFilials[$filial["CODE"]] = $filial["NAME"];
                            endwhile;
                            ?>
							<?global $city;?>

                            <?$elems = CIBlockElement::GetList(array(),array("IBLOCK_ID" => 12, "ACTIVE" => "Y", "SECTION_ID" => $getCat['UF_DIRS_WITH_ITEMS'] ? $getCat['UF_DIRS_WITH_ITEMS'] : $getCat["ID"], "PROPERTY_REGION" => $arFilials[$city]),false,false,array("ID","IBLOCK_ID","NAME","PROPERTY_SIZE","PROPERTY_GOST","PROPERTY_STAL_MARK","PROPERTY_REGION","PROPERTY_PRICE_PM","PROPERTY_PRICE_SHT","PROPERTY_PRICE_TONN","PROPERTY_WEIGHT_KG","PROPERTY_LENGTH_M"));
							while($elem = $elems->fetch()):
								if(empty($elem["PROPERTY_REGION_VALUE"])) continue;?>
								<?php if($elem["PROPERTY_REGION_VALUE"] != "Нижний Новгород"):?>
								<!--noindex-->
								<?php endif; ?>	
                                <tr class="list-item">
                                    <td class="name-prod"><img width="31" src="<?=$icon?>" class="icon-list"/><span style="float: right;width: 190px;"><a class="a-items" href=""><?=$elem["NAME"]?></a></span></td>
                                    <td><?=$elem["PROPERTY_SIZE_VALUE"]?></td>
                                    <td><?=$elem["PROPERTY_GOST_VALUE"]?></td>
                                    <td><?=$elem["PROPERTY_STAL_MARK_VALUE"]?></td>
                                    <td><?=($elem["PROPERTY_REGION_VALUE"] == "Нижний Новгород")?"Н.Новгород":$elem["PROPERTY_REGION_VALUE"]?></td>
                                    <td><?=(is_numeric($elem["PROPERTY_PRICE_TONN_VALUE"])) ? round($elem["PROPERTY_PRICE_PM_VALUE"],1)." руб." : "Под заказ" ?></td>
                                    <td class="cart"><?=(is_numeric($elem["PROPERTY_PRICE_TONN_VALUE"])) ? round($elem["PROPERTY_PRICE_SHT_VALUE"],1)." руб." : "Под заказ"?><span onclick="yaCounter44354200.reachGoal('dobavit-v-korzinu'); return true;" data-id="<?=$elem["ID"]?>" data-type="<?=$type?>" data-weight="<?=$elem["PROPERTY_WEIGHT_KG_VALUE"]?>" data-length="<?=$elem["PROPERTY_LENGTH_M_VALUE"]?>" data-price_sht="<?=$elem["PROPERTY_PRICE_SHT_VALUE"]?>" data-price_pm="<?=$elem["PROPERTY_PRICE_PM_VALUE"]?>" class="open-modal <?=(is_numeric($elem["PROPERTY_PRICE_TONN_VALUE"])) ? "cart-prise" : "no-prise"?>"></span></td>
								</tr>
								<?php if($elem["PROPERTY_REGION_VALUE"] != "Нижний Новгород"):?>
								<!--/noindex-->
								<?php endif; ?>	
							<?endwhile;?>
							</tbody>
							<tbody>
							<?$elems = CIBlockElement::GetList(array(),array("IBLOCK_ID" => 12, "ACTIVE" => "Y", "SECTION_ID" => $getCat['UF_DIRS_WITH_ITEMS'] ? $getCat['UF_DIRS_WITH_ITEMS'] : $getCat["ID"], "!PROPERTY_REGION" => $arFilials[$city]),false,false,array("ID","IBLOCK_ID","NAME","PROPERTY_SIZE","PROPERTY_GOST","PROPERTY_STAL_MARK","PROPERTY_REGION","PROPERTY_PRICE_PM","PROPERTY_PRICE_SHT","PROPERTY_PRICE_TONN","PROPERTY_WEIGHT_KG","PROPERTY_LENGTH_M"));
							while($elem = $elems->fetch()):
								if(empty($elem["PROPERTY_REGION_VALUE"])) continue;?>
								<?php if($elem["PROPERTY_REGION_VALUE"] != "Нижний Новгород"):?>
								<!--noindex-->
								<?php endif; ?>	
								<tr class="list-item" style="display: none;">
									<td class="name-prod"><img width="31" src="<?=$icon?>" class="icon-list"/><span style="float: right;width: 190px;"><a class="a-items" href=""><?=$elem["NAME"]?></a></span></td>
									<td><?=$elem["PROPERTY_SIZE_VALUE"]?></td>
									<td><?=$elem["PROPERTY_GOST_VALUE"]?></td>
									<td><?=$elem["PROPERTY_STAL_MARK_VALUE"]?></td>
									<td><?=($elem["PROPERTY_REGION_VALUE"] == "Нижний Новгород")?"Н.Новгород":$elem["PROPERTY_REGION_VALUE"]?></td>
									<td><?=(is_numeric($elem["PROPERTY_PRICE_TONN_VALUE"])) ? round($elem["PROPERTY_PRICE_PM_VALUE"],1)." руб." : "Под заказ" ?></td>
									<td class="cart"><?=(is_numeric($elem["PROPERTY_PRICE_TONN_VALUE"])) ? round($elem["PROPERTY_PRICE_SHT_VALUE"],1)." руб." : "Под заказ"?><span onclick="yaCounter44354200.reachGoal('dobavit-v-korzinu'); return true;" data-id="<?=$elem["ID"]?>" data-type="<?=$type?>" data-weight="<?=$elem["PROPERTY_WEIGHT_KG_VALUE"]?>" data-length="<?=$elem["PROPERTY_LENGTH_M_VALUE"]?>" data-price_sht="<?=$elem["PROPERTY_PRICE_SHT_VALUE"]?>" data-price_pm="<?=$elem["PROPERTY_PRICE_PM_VALUE"]?>" class="open-modal <?=(is_numeric($elem["PROPERTY_PRICE_TONN_VALUE"])) ? "cart-prise" : "no-prise"?>"></span></td>
								</tr>
								<?php if($elem["PROPERTY_REGION_VALUE"] != "Нижний Новгород"):?>
								<!--/noindex-->
								<?php endif; ?>	
							<?endwhile;?>
							 </tbody>
						</table>
					</div>
				<?endif;?>

		</div>
</main>
<div id="form-feedback" class="form-add-cart" style="display:none;">
	<div id="init-form" class="hidden-modal">
		<form id="anm-form" class="form form-horizontal " name="order" enctype="multipart/form-data" action="javascript:void(null);" method="POST">
            <input type="hidden" id="prod_id" name="product_id" value="" />
            <input type="hidden" id="prod_type" value="" />
            <input type="hidden" id="prod_weight" value="" />
            <input type="hidden" id="prod_length" value="" />
			<span class="close">X</span>
			<div class="add-name-prod"></div>
			<div class="info-data">
				<div class="pole-anm tonni">
					<p>Количество, шт.</p>
					<input id="calc_sht" required="" type="number" data-price="" class="inputbox" name="sht" value="">
				</div>
                <!--<div class="pole-anm price">
					<p>Цена за шт.</p>
					<input readonly type="text" class="inputbox" name="name" value="">
				</div>-->
				<div class="pole-anm metri">
					<p>Количество, кв.м./п.м.</p>
					<input id="calc_pm" required="" type="number" data-price="" class="inputbox" name="pm" value="">
				</div>
				<!--<div class="pole-anm price">
					<p>Цена за кв.м./п.м.</p>
					<input readonly type="text" class="inputbox" name="name" value="">
				</div>-->
				<div class="pole-anm all-price">
					<p>Сумма</p>
					<input id="calc_sum" readonly required="" type="text" class="inputbox" name="sum" value="">
				</div>
				<input type="submit" id="send-cart" class="button " value="Добавить в корзину">
			</div>
            <?if(!empty($getCat["UF_PRIMECHANIE"])):?>
    			<div class="primechanie">
    				<p><span>Примечание:</span> <?=$getCat["UF_PRIMECHANIE"]?></p>
    			</div>
            <?else:
	            $getParentCat = CIBlockSection::GetList(array(),array("IBLOCK_ID" => 12, "ID" => $getCat["IBLOCK_SECTION_ID"]),false,array("ID","NAME","UF_ICON","UF_PRIMECHANIE"))->fetch();
            ?>
            	<?if(!empty($getParentCat["UF_PRIMECHANIE"])):?>
	    			<div class="primechanie">
	    				<p><span>Примечание:</span> <?=$getParentCat["UF_PRIMECHANIE"]?></p>
	    			</div>
    			<?endif;?>
            <?endif;?>
		</form>
	</div>
</div>

<div id="form-feedback-ticket" class="form-add-cart" style="display:none;">
	<div id="init-form-ticket" class="hidden-modal">
		<form id="anm-form-ticket" class="form form-horizontal " name="order" enctype="multipart/form-data" action="javascript:void(null);" method="POST">
            <input type="hidden" id="prod_id-ticket" name="product_id" value="" />
            <span class="close">X</span>
			<div class="add-name-prod"></div>
			<div class="info-data">
				<div class="pole-anm tonni">
					<p>Имя</p>
					<input required="" type="text" class="inputbox" name="name" value="">
				</div>
				<div class="pole-anm metri">
					<p>Телефон</p>
					<input required="" type="text" class="inputbox" name="phone" value="">
				</div>
				<div class="pole-anm all-price">
					<p>Почта</p>
					<input required="" type="email" class="inputbox" name="email" value="">
				</div>
				<div class="pole-anm all-price">
					<p>Комментарий</p>
					<input type="text" class="inputbox" name="comment" value="">
				</div>
				<input type="submit" id="send-cart-ticket" class="button " value="Отправить">
			</div>
			<div class="primechanie">
				<p><span>Примечание:</span> Отправьте заявку и наш менеджер обсудит с вами условия получения необходимого товара</p>
			</div>
		</form>
	</div>
</div>

<div id="form-feedback-hs" class="form-add-cart" style="display:none;">
	<div id="init-form-hs" class="hidden-modal">
		<form id="anm-form-hs" class="form form-horizontal " name="order" enctype="multipart/form-data" action="javascript:void(null);" method="POST">
            <input type="hidden" name="type" value="<?=$type?>">
            <span class="close">X</span>
			<div class="add-name-prod">Оставьте заявку</div>
            <table id="hs_table">
            <colgroup width="400">
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Тоннаж</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
			<div class="info-data">
				<div class="pole-anm tonni">
					<p>Имя</p>
					<input required="" type="text" class="inputbox" name="name" value="">
				</div>
				<div class="pole-anm metri">
					<p>Телефон</p>
					<input required="" type="text" class="inputbox" name="phone" value="">
				</div>
				<div class="pole-anm all-price">
					<p>Почта</p>
					<input required="" type="email" class="inputbox" name="email" value="">
				</div>
				<div class="pole-anm all-price">
					<p>Комментарий</p>
					<input type="text" class="inputbox" name="comment" value="">
				</div>
				<input type="submit" id="send-cart-hs" class="button " value="Отправить">
			</div>
		</form>
	</div>
</div>

<div id="overlay"></div>
<?php //CJSCore::Init(array("jquery"));
 ?>
<script>
//console.log("start");

$( document ).ready(function() {
  // Handler for .ready() called.
  //alert(jQuery.fn.jquery);
  //console.log("hi");
  //$("tr.list-item").wrap("<noindex></noindex>");
});
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
