<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?

use AI\R52;
use Shuchkin\SimpleXLS;

if ( $xlsx = SimpleXLS::parse('check_list.xls') ) {
    R52::debug( $xlsx->rows() );
} else {
    echo SimpleXLS::parseError();
}

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>