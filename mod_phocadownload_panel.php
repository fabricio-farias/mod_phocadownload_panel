<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_custom
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';

if ($params->def('prepare_content', 1))
{
	JPluginHelper::importPlugin('content');
	$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_phocadownload_panel.content');
}


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

JFactory::getDocument()->addStyleSheet(JURI::base() . 'modules/mod_phocadownload_panel/css/style.css');
JFactory::getDocument()->addStyleSheet(JURI::base() . 'components/com_roksprocket/layouts/tabs/themes/default/tabs.css');

//JFactory::getDocument()->addScript(JURI::base() . 'components/com_roksprocket/assets/js/moofx.js');
//JFactory::getDocument()->addScript(JURI::base() . 'components/com_roksprocket/assets/js/roksprocket.js');
//JFactory::getDocument()->addScript(JURI::base() . 'components/com_roksprocket/layouts/tabs/themes/default/tabs.js');
//JFactory::getDocument()->addScript(JURI::base() . 'modules/mod_phocadownload_panel/js/jquery.js');


$doc = JFactory::getDocument();
//$js = "
//    window.addEvent('domready', function(){
//        RokSprocket.instances.tabs = new RokSprocket.Tabs();
//    });
//    window.addEvent('domready', function(){
//        RokSprocket.instances.tabs.attach(2014, '{\"autoplay\":\"0\",\"delay\":\"10\"}');
//    });
//  ";
//$doc->addScriptDeclaration($js);
$doc->addScript(JURI::base() . 'modules/mod_phocadownload_panel/js/bootstrap-tab.js');
$doc->addScript(JURI::base() . 'modules/mod_phocadownload_panel/js/bootstrap-transition.js');

//require JModuleHelper::getLayoutPath('mod_phocadownload_panel', $params->get('layout', 'default'));

$layoutModulePath = JModuleHelper::getLayoutPath('mod_phocadownload_panel', $params->get('layout', 'default'));
$classLocations = new modPhocadownloadPanelHelper($params);
$locations = $classLocations->getLocationsList();

require ($layoutModulePath);
