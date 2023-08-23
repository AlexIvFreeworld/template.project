<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the news functions only once
JLoader::register('ModArticlesNewsHelper', __DIR__ . '/helper.php');
// $params->catid = array(14);
$list            = ModArticlesNewsHelper::getList($params);
$params2 = $params;
$params2->set('catid', array(14));
$list2            = ModArticlesNewsHelper::getList($params2);
$params3 = $params;
$params3->set('catid', array(21));
$list3            = ModArticlesNewsHelper::getList($params3);
// echo "<pre>";print_r($list3);echo "</pre>";

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_articles_news', $params->get('layout', 'horizontal'));
