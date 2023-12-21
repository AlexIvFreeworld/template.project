<?
if($_SERVER["REQUEST_URI"] == "/?"){
    header("Location:/",TRUE,301);
}
if($APPLICATION->GetCurPage() == "/index/"){
    header("Location:/",TRUE,301);
}
if(HIDE_SECTION){
	header("Location:/404.php",404);
}