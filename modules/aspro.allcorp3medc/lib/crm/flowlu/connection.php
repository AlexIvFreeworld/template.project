<?
namespace Aspro\Allcorp3Medc\CRM\Flowlu;

use Aspro\Allcorp3Medc\CRM,
    CAllcorp3Medc as Solution;

class Connection extends CRM\Acloud\Connection {
    protected static $instances = [];

    public static function getType() {
        return CRM\Type::FLOWLU;
    }

    public static function fixDomain(string $domain) {
        $domain = trim($domain);
        $domain = preg_replace('/\/*$/', '', $domain);

        if (strlen($domain)) {
            $domain = 'https://'.preg_replace('/https?:\/\//i', '', $domain);

            if (strpos($domain, '.') === false) {
                $domain .= '.flowlu.ru';
            }
        }

        return $domain;
    }
}