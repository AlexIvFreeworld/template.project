<?
use Bitrix\Main\Entity,
   \Bitrix\Main\Config\Option;

class CAllcorp3MedcChartTable extends Entity\DataManager
{
   private static $moduleName = 'aspro.allcorp3medc';
   private static $viewMode = array();
   public static $siteID = 's1';

   const LEFT_TO_RIGHT = 'Y';
   const RIGHT_TO_LEFT = 'N';

   public static function viewMode($site_id = '')
   {
      if( !$site_id )
         $site_id = self::$siteID;
      
      if( !isset($viewMode[$site_id]) || !$viewMode[$site_id] ) {
         self::$viewMode[$site_id] = Option::get(self::$moduleName, 'CHART_VIEW', 'MOUNTH', $site_id);
      }
      return self::$viewMode[$site_id];
   }

   public static function getFilePath()
   {
      return __FILE__;
   }

   public static function getTableName()
   {
      if(self::viewMode(self::$siteID) == 'MOUNTH') {
         return 'aspro_allcorp3medc_chart';
      } else if(self::viewMode(self::$siteID) == 'REGULAR') {
         return 'aspro_allcorp3medc_chart_regular';
      }  
   }

   public static function checkFields($entity_id, $ID, $arFields, $user_id = false, $checkRequired = true, array $requiredFields = null)
   {
      return true;
   }

   public static function getMap()
   {
      return array(
         'ID' => array(
            'data_type' => 'integer',
            'primary' => true,
            'autocomplete' => true,
         ),
         'SERVICE_ID' => array(
            'data_type' => 'integer',
         ),
         'STAFF_ID' => array(
            'data_type' => 'integer',
         ),
         'DATE' => array(
            'data_type' => 'date',
         ),
         'SHOP_ID' => array(
            'data_type' => 'integer',
         ),
         'WORK_TIME' => array(
            'data_type' => 'string',
         ),
         'SITE_ID' => array(
            'data_type' => 'string',
         ),
      );
   }
}
?>